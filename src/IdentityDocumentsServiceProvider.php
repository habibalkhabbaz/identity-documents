<?php

namespace HabibAlkhabbaz\IdentityDocuments;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;

class IdentityDocumentsServiceProvider extends ServiceProvider
{
    /**
     * The console commands.
     *
     * @var bool
     */
    protected $commands = [
        'HabibAlkhabbaz\IdentityDocuments\Commands\MakeService',
    ];

    /**
     * Bootstrap the application events.
     *
     * @throws BindingResolutionException
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/identity_documents.php' => config_path('identity_documents.php'),
        ]);
    }

    /**
     * Register the command.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/identity_documents.php', 'identity_documents');
        $this->mergeConfigFrom(__DIR__.'/../config/countries.php', 'id_countries');
        $this->commands($this->commands);
    }
}
