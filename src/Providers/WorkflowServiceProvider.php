<?php

namespace Wuwx\LaravelWorkflow\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\User;
use Livewire\Livewire;
use Wuwx\LaravelWorkflow\Factories\RegistryFactory;
use Wuwx\LaravelWorkflow\Http\Livewire\Workflow;

class WorkflowServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Workflow';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'workflow';

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
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        if ($this->app['config']->get('workflow.debug') || true) {
            $this->app->register(RouteServiceProvider::class);
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../resources/js/components' => base_path('resources/js/components/workflow'),
            ], 'workflow-components');

            $this->commands([
                'Wuwx\LaravelWorkflow\Console\ListCommand',
                'Wuwx\LaravelWorkflow\Console\InfoCommand',
                'Wuwx\LaravelWorkflow\Console\DumpCommand',
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
        Blueprint::macro("workflow", function() {
            $this->string('workflow_name')->nullable();
            $this->string('workflow_marking')->nullable();
            $this->string('workflow_version')->nullable();
        });

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

        Livewire::component('workflow', Workflow::class);
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
        $viewPath = resource_path('views/vendor/workflow');

        $sourcePath = __DIR__.'/../../resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/vendor/workflow';
        }, \Config::get('view.paths')), [$sourcePath]), 'workflow');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
    }
}
