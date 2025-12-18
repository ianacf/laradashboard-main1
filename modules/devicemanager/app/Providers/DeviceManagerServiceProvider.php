<?php

declare(strict_types=1);

namespace Modules\DeviceManager\Providers;

use App\Enums\Hooks\AdminFilterHook;
use App\Services\MenuService\AdminMenuItem;
use App\Support\Facades\Hook;
use App\Support\HookManager;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\DeviceManager\Enums\Hooks\DeviceHook;
use Modules\DeviceManager\Models\Device;
use Modules\DeviceManager\Policies\DevicePolicy;
use Nwidart\Modules\Traits\PathNamespace;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class DeviceManagerServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'DeviceManager';

    protected string $nameLower = 'devicemanager';

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
            Hook::addFilter(AdminFilterHook::ADMIN_MENU_GROUPS_BEFORE_SORTING, [$this, 'addDeviceManagerMenu']);
            $this->registerDeviceHooks();
        });
    }

    public function addDeviceManagerMenu(array $groups): array
    {
        $childMenusItems = [
            (new AdminMenuItem())->setAttributes([
                'label' => __('Devices'),
                'route' => route('admin.devices.index'),
                'active' => Route::is('admin.devices.index') || Route::is('admin.devices.edit'),
                'priority' => 1,
                'id' => 'devices_manager_index',
                'permissions' => ['device.view'],
            ]),
            (new AdminMenuItem())->setAttributes([
                'label' => __('New Device'),
                'route' => route('admin.devices.create'),
                'active' => Route::is('admin.devices.create'),
                'priority' => 2,
                'id' => 'devices_manager_create',
                'permissions' => ['device.create'],
            ]),
        ];

        $adminMenuItem = (new AdminMenuItem())->setAttributes([
            'label' => __('Device Manager'),
            'icon' => 'lucide:list-todo',
            'route' => route('admin.devices.index'),
            'active' => Route::is('admin.devices.*'),
            'id' => 'device-manager',
            'priority' => 21,
            'permissions' => ['device.view', 'device.create', 'device.edit', 'device.delete'],
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
        Gate::policy(Device::class, DevicePolicy::class);
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
        $langPath = resource_path('lang/modules/' . $this->nameLower);

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
                    $config = str_replace($configPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                    $config_key = str_replace([DIRECTORY_SEPARATOR, '.php'], ['.', ''], $config);
                    $segments = explode('.', $this->nameLower . '.' . $config_key);

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
        $viewPath = resource_path('views/modules/' . $this->nameLower);
        $sourcePath = module_path($this->name, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->nameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->nameLower);

        Blade::componentNamespace(config('modules.namespace') . '\\' . $this->name . '\\View\\Components', $this->nameLower);
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
            if (is_dir($path . '/modules/' . $this->nameLower)) {
                $paths[] = $path . '/modules/' . $this->nameLower;
            }
        }

        return $paths;
    }

    /**
     * Register device hooks for Device Manager module.
     */
    protected function registerDeviceHooks(): void
    {
        $hookManager = app(HookManager::class);

        // Example: Handle device creation
        $hookManager->addAction(DeviceHook::CREATED, function ($device) {
            // Send notification when device is created
        });

        // Example: Handle device updates
        $hookManager->addAction(DeviceHook::UPDATED, function ($device) {
            // Log device update activity
        });

        // Example: Handle device assignment
        $hookManager->addAction(DeviceHook::ASSIGNED, function ($device, $user) {
            // Notify assigned user
        });

        // Example: Filter device status options
        $hookManager->addFilter(DeviceHook::STATUS_OPTIONS, function ($statuses) {
            // Add custom status options for devices
            return $statuses;
        });

        // Example: Filter assignable users
        $hookManager->addFilter(DeviceHook::ASSIGNABLE_USERS, function ($users) {
            // Filter users who can be assigned to devices
            return $users;
        });
    }
}

