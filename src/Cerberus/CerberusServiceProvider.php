<?php
/**
 * CerberusServiceProvider.php
 * Modified from https://github.com/rydurham/Sentinel
 * by anonymous on 12/01/16 22:33.
 */

namespace Cerberus;

use Artisan;
use Hashids\Hashids;
use ReflectionClass;
use Cerberus\Commands\CerberusPublishCommand;
use Cerberus\Repositories\Session\CarbuncleSessionRepository;
use Cerberus\Repositories\Group\CarbuncleGroupRepository;
use Cerberus\Repositories\User\CarbuncleUserRepository;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class CerberusServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Find path to the package
        $cerberusFilename = with(new ReflectionClass('Cerberus\CerberusServiceProvider'))->getFileName();
        $cerberusPath     = dirname($cerberusFilename);

        // Register Artisan Commands
        $this->registerArtisanCommands();

        // Establish Fallback Config settings
        $this->mergeConfigFrom($cerberusPath.'/../config/cerberus.php', 'cerberus');
        $this->mergeConfigFrom($cerberusPath.'/../config/carbuncle.php', 'carbuncle');

        // Establish Views Namespace
        if (is_dir(base_path() . '/resources/views/cerberus')) {
            // The package views have been published - use those views.
            $this->loadViewsFrom(base_path() . '/resources/views/cerberus', 'Cerberus');
        } else {
            // The package views have not been published. Use the defaults.
            $this->loadViewsFrom($cerberusPath . '/../views/bootstrap', 'Cerberus');
        }

        // Establish Translator Namespace
        $this->loadTranslationsFrom($cerberusPath . '/../lang', 'Cerberus');

        // Include custom validation rules
        include $cerberusPath . '/../validators.php';

        // Should we register the default routes?
        if (config('cerberus.routes_enabled')) {
            include $cerberusPath . '/../routes.php';
        }

        // Set up event listeners
        $dispatcher = $this->app->make('events');
        $dispatcher->subscribe('Cerberus\Listeners\UserEventListener');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register the Carbuncle Service Provider
        $this->app->register('Cerberus\CarbuncleServiceProvider');

       // Register the Vinkla/Hashids Service Provider
        $this->app->register('Vinkla\Hashids\HashidsServiceProvider');

        // Load the Carbuncle and Hashid Facade Aliases
        $loader = AliasLoader::getInstance();
        $loader->alias('Carbuncle', 'Einherjars\Carbuncle\Facades\Laravel\Carbuncle');
        $loader->alias('Hashids', 'Vinkla\Hashids\Facades\Hashids');


        // Bind the User Repository
        $this->app->bind('Cerberus\Repositories\User\CerberusUserRepositoryInterface', function ($app) {
            return new CarbuncleUserRepository(
                $app['carbuncle'],
                $app['config'],
                $app['events']
            );
        });

        // Bind the Group Repository
        $this->app->bind('Cerberus\Repositories\Group\CerberusGroupRepositoryInterface', function ($app) {
            return new CarbuncleGroupRepository(
                $app['carbuncle'],
                $app['events']
            );
        });

        // Bind the Session Manager
        $this->app->bind('Cerberus\Repositories\Session\CerberusSessionRepositoryInterface', function ($app) {
            return new CarbuncleSessionRepository(
                $app['carbuncle'],
                $app['events']
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('auth', 'carbuncle');
    }

    /**
     * Register the Artisan Commands
     */
    private function registerArtisanCommands()
    {
        $this->app['cerberus.publisher'] = $this->app->share(function ($app) {
            return new CerberusPublishCommand(
                $app->make('files')
            );
        });

        $this->commands('cerberus.publisher');
    }
}
