<?php

namespace Chapdel\AuthLog;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Command;

class AuthLogServiceProvider extends ServiceProvider
{
    use EventMap;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerEvents();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'authlog');

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'authlog');

        $this->mergeConfigFrom(__DIR__.'/../config/authlog.php', 'authlog');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'authlog-migrations');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/authlog'),
            ], 'authlog-views');

            $this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/authlog'),
            ], 'authlog-translations');

            $this->publishes([
                __DIR__.'/../config/authlog.php' => config_path('authlog.php'),
            ], 'authlog-config');

            $this->commands([
                Console\ClearCommand::class,
            ]);


        }

        \Artisan::call('vendor:publish --provider="Spatie\WelcomeNotification\WelcomeNotificationServiceProvider --tag="migrations"');
    }

    /**
     * Register the Authentication Log's events.
     *
     * @return void
     */
    protected function registerEvents()
    {
        $events = $this->app->make(Dispatcher::class);

        foreach ($this->events as $event => $listeners) {
            foreach ($listeners as $listener) {
                $events->listen($event, $listener);
            }
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\ClearCommand::class,
            ]);
        }
    }
}
