<?php

declare(strict_types=1);

namespace Modules\Esp32data\Providers;

use App\Enums\Hooks\AdminFilterHook;
use App\Services\MenuService\AdminMenuItem;
use App\Support\Facades\Hook;
use App\Support\HookManager;										
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;									
use Illuminate\Support\ServiceProvider;
use Modules\Esp32data\Enums\Hooks\Esp32Hook;
use Modules\Esp32data\Models\Esp32;
use Modules\Esp32data\Policies\Esp32Policy;											 
use Nwidart\Modules\Traits\PathNamespace;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Esp32dataServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Esp32data';

    protected string $nameLower = 'esp32data';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerPolicies();								  
        $this->loadMigrationsFrom(module_path($this->name, 'database/migrations'));
        $this->app->booted(function () {
            Hook::addFilter(AdminFilterHook::ADMIN_MENU_GROUPS_BEFORE_SORTING, [$this, 'addEsp32dataMenu']);
            $this->registerEsp32Hooks();
        });
    }

    public function addEsp32dataMenu(array $groups): array
    {
        $childMenusItems = [
            (new AdminMenuItem())->setAttributes([
                'label' => __('Esp32s'),
                'route' => route('admin.esp32s.index'),
                'active' => Route::is('admin.esp32s.index') || Route::is('admin.esp32s.edit'),
                'priority' => 1,
                'id' => 'esp32s_manager_index',
                'permissions' => ['esp32.view'],
            ]),
            (new AdminMenuItem())->setAttributes([
                'label' => __('New Esp32'),
                'route' => route('admin.esp32s.create'),
                'active' => Route::is('admin.esp32s.create'),
                'priority' => 2,
                'id' => 'esp32s_manager_create',
                'permissions' => ['esp32.create'],
            ]),
        ];

        $adminMenuItem = (new AdminMenuItem())->setAttributes([
            'label' => __('Esp32 Data'),
            'icon' => 'lucide:database',
            'route' => route('admin.esp32s.index'),
            'active' => Route::is('admin.esp32s.*'),
            'id' => 'esp32data',
            'priority' => 21,
            'permissions' => ['esp32.view'],
            'children' => $childMenusItems,
        ]);

        $groups[__('Main')][] = $adminMenuItem;

        return $groups;
    }										

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {
        // $this->commands([]);
    }

    /**
     * Register the module policies.
     */
    protected function registerPolicies(): void
    {
        Gate::policy(Esp32::class, Esp32Policy::class);
    }
     
    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {
        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->command('inspire')->hourly();
        // });
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/'.$this->nameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->nameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->name, 'lang'), $this->nameLower);
            $this->loadJsonTranslationsFrom(module_path($this->name, 'lang'));
        }
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $configPath = module_path($this->name, config('modules.paths.generator.config.path'));

        if (is_dir($configPath)) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($configPath));

            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $config = str_replace($configPath.DIRECTORY_SEPARATOR, '', $file->getPathname());
                    $config_key = str_replace([DIRECTORY_SEPARATOR, '.php'], ['.', ''], $config);
                    $segments = explode('.', $this->nameLower.'.'.$config_key);

                    // Remove duplicated adjacent segments
                    $normalized = [];
                    foreach ($segments as $segment) {
                        if (end($normalized) !== $segment) {
                            $normalized[] = $segment;
                        }
                    }

                    $key = ($config === 'config.php') ? $this->nameLower : implode('.', $normalized);

                    $this->publishes([$file->getPathname() => config_path($config)], 'config');
                    $this->merge_config_from($file->getPathname(), $key);
                }
            }
        }
    }

    /**
     * Merge config from the given path recursively.
     */
    protected function merge_config_from(string $path, string $key): void
    {
        $existing = config($key, []);
        $module_config = require $path;

        config([$key => array_replace_recursive($existing, $module_config)]);
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/'.$this->nameLower);
        $sourcePath = module_path($this->name, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->nameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->nameLower);

        Blade::componentNamespace(config('modules.namespace').'\\' . $this->name . '\\View\\Components', $this->nameLower);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path.'/modules/'.$this->nameLower)) {
                $paths[] = $path.'/modules/'.$this->nameLower;
            }
        }

        return $paths;
    }

    /**
     * Register esp32 hooks for Esp32data module.
     */
    protected function registerEsp32Hooks(): void
    {
        $hookManager = app(HookManager::class);

        // Example: Handle esp32 creation
        $hookManager->addAction(Esp32Hook::CREATED, function ($esp32) {
            // Send notification when esp32 is created
        });
    }
}
