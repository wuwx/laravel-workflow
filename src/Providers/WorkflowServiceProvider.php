<?php

namespace Wuwx\LaravelWorkflow\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Factory;

use App\User;
use Wuwx\LaravelWorkflow\Factories\RegistryFactory;

class WorkflowServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        if ($this->app['config']->get('workflow.debug') || true) {
            $this->app->register(RouteServiceProvider::class);
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../resources/assets/js/components' => base_path('resources/assets/js/components/workflow'),
            ], 'workflow-components');

            $this->commands([
                'Wuwx\LaravelWorkflow\Console\ListCommand',
                'Wuwx\LaravelWorkflow\Console\InfoCommand',
            ]);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('workflow.registry', function ($app) {
            return RegistryFactory::make();
        });

        $this->app->register(EventServiceProvider::class);

        Gate::define("apply", function (User $user, $transition) {
            foreach($user->members as $member) {
                if (array_intersect($member->role_ids, $transition->role_ids)) {
                    return true;
                }
            }
        });
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../../config/config.php' => config_path('workflow.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../../config/config.php', 'workflow'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/workflow');

        $sourcePath = __DIR__.'/../../resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/workflow';
        }, \Config::get('view.paths')), [$sourcePath]), 'workflow');
    }

    /**
     * Register an additional directory of factories.
     * @source https://github.com/sebastiaanluca/laravel-resource-flow/blob/develop/src/Modules/ModuleServiceProvider.php#L66
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../../database/factories');
        }
    }
}
