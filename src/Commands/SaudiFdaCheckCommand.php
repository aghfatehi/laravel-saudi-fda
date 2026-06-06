<?php

namespace Aghfatehi\SaudiFda\Commands;

use Aghfatehi\SaudiFda\SaudiFdaClient;
use Illuminate\Console\Command;

class SaudiFdaCheckCommand extends Command
{
    protected $signature = 'saudi-fda:check
        {--auth : Check authentication only}
        {--config : Display current configuration values}';

    protected $description = 'Check SFDA package configuration and connectivity';

    public function handle(SaudiFdaClient $client): int
    {
        if ($this->option('config')) {
            return $this->displayConfig($client);
        }

        return $this->checkAll($client);
    }

    private function checkAll(SaudiFdaClient $client): int
    {
        $this->components->info('SFDA Package Health Check');
        $this->newLine();

        $this->components->twoColumnDetail('Package Version', '1.0.0');
        $this->components->twoColumnDetail('Environment', $client->environment()->value);
        $this->newLine();

        $this->components->twoColumnDetail(
            'Credentials Configured',
            $client->isConfigured() ? '<fg=green>YES</>' : '<fg=red>NO</>'
        );

        if (!$client->isConfigured()) {
            $this->components->error(
                'Missing credentials. Set SFDA_CONSUMER_KEY and SFDA_CONSUMER_SECRET in your .env file.'
            );
            return Command::FAILURE;
        }

        if ($this->option('auth')) {
            return $this->checkAuth($client);
        }

        $this->checkAuth($client);

        return Command::SUCCESS;
    }

    private function checkAuth(SaudiFdaClient $client): int
    {
        $this->components->task('Testing SFDA Authentication', function () use ($client) {
            try {
                $token = $client->auth()->getAccessToken();
                $this->components->twoColumnDetail('Token', substr($token->accessToken, 0, 30) . '...');
                $this->components->twoColumnDetail('Expires In', $token->expiresIn . ' seconds');
                return true;
            } catch (\Throwable $e) {
                $this->line('');
                $this->components->error($e->getMessage());
                return false;
            }
        });

        return Command::SUCCESS;
    }

    private function displayConfig(SaudiFdaClient $client): int
    {
        $this->components->info('SFDA Configuration');

        $this->newLine();
        $this->components->twoColumnDetail('environment', config('saudi-fda.environment'));
        $this->components->twoColumnDetail('consumer_key', $this->mask(config('saudi-fda.credentials.consumer_key')));
        $this->components->twoColumnDetail('consumer_secret', $this->mask(config('saudi-fda.credentials.consumer_secret')));
        $this->components->twoColumnDetail('token_cache.store', config('saudi-fda.token_cache.store'));
        $this->components->twoColumnDetail('token_cache.enabled', config('saudi-fda.token_cache.enabled') ? 'true' : 'false');
        $this->components->twoColumnDetail('timeout', config('saudi-fda.api.timeout') . 's');
        $this->components->twoColumnDetail('logging.enabled', config('saudi-fda.logging.enabled') ? 'true' : 'false');
        $this->components->twoColumnDetail('routes.enabled', config('saudi-fda.routes.enabled') ? 'true' : 'false');
        $this->components->twoColumnDetail('routes.prefix', config('saudi-fda.routes.prefix'));

        return Command::SUCCESS;
    }

    private function mask(?string $value): string
    {
        if (empty($value)) {
            return '<fg=red>not set</>';
        }

        return substr($value, 0, 4) . '****' . substr($value, -4);
    }
}
