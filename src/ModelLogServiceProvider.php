<?php

namespace Dostontiu\ModelLog;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider as Provider;

class ModelLogServiceProvider extends Provider
{
    public function boot()
    {
        $this->publish();
    }

    protected function publish()
    {
        $this->publishes([
            __DIR__.'/../config/model-log.php' => $this->app->configPath('model-log.php'),
        ], 'model-log-config');

        $this->publishes([
            __DIR__.'/../database/migrations/create_model_logs_table.php.stub' => $this->migrationFileName('create_model_logs_table.php'),
        ], 'model-log-migrations');
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     */
    protected function migrationFileName($migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
                return $filesystem->glob($path.'*_'.$migrationFileName);
            })
            ->push($this->app->databasePath()."/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }
}
