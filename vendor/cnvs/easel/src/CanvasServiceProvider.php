<?php

namespace Canvas;

use Schema;
use Carbon\Carbon;
use Canvas\Models\Settings;
use Canvas\Helpers\RouteHelper;
use Canvas\Helpers\SetupHelper;
use Canvas\Helpers\CanvasHelper;
use Canvas\Helpers\ConfigHelper;
use Canvas\Console\Commands\Index;
use Canvas\Console\Commands\Theme;
use Canvas\Console\Commands\Update;
use Canvas\Console\Commands\Install;
use Canvas\Console\Commands\Version;
use Maatwebsite\Excel\Facades\Excel;
use Canvas\Console\Commands\Uninstall;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\ScoutServiceProvider;
use Canvas\Http\Middleware\CheckIfAdmin;
use Canvas\Console\Commands\Publish\Views;
use Canvas\Console\Commands\Publish\Assets;
use Canvas\Console\Commands\Publish\Config;
use Canvas\Http\Middleware\EnsureInstalled;
use Maatwebsite\Excel\ExcelServiceProvider;
use Canvas\Models\Observers\SettingsObserver;
use Canvas\Http\Middleware\EnsureNotInstalled;
use Canvas\Console\Commands\Publish\Migrations;
use Canvas\Extensions\ExtensionsServiceProvider;
use TeamTNT\Scout\TNTSearchScoutServiceProvider;
use Canvas\Console\Commands\Publish\Translations;
use Canvas\Http\Middleware\CheckForMaintenanceMode;
use Larapack\ConfigWriter\Repository as ConfigWriter;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use TalvBansal\MediaManager\Providers\MediaManagerServiceProvider;

class CanvasServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * List of commands.
     *
     * @var array
     */
    protected $commands = [
        Index::class,
        Views::class,
        Theme::class,
        Update::class,
        Config::class,
        Assets::class,
        Install::class,
        Version::class,
        Uninstall::class,
        Translations::class,
    ];

    /**
     * Public asset files.
     */
    private function handleAssets()
    {
        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/canvas'),
        ], 'public');
    }

    /**
     * Configuration files.
     */
    private function handleConfigs()
    {
        $configPath = __DIR__.'/../config/blog.php';

        // Allow publishing the config file, with tag: config
        $this->publishes([$configPath => config_path('blog.php')], 'config');

        // Merge config files...
        // Allows any modifications from the published config file to be seamlessly merged with default config file
        $this->mergeConfigFrom($configPath, 'blog');
    }

    /**
     * Translation files.
     */
    private function handleTranslations()
    {
        // Set locale for Carbon
        Carbon::setLocale(config('app.locale'));

        // Load translations...
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'canvas');

        // Allow publishing translation files, with tag: translations
        $this->publishes([
            __DIR__.'/../resources/lang' => base_path('resources/lang/vendor/canvas'),
        ], 'translations');
    }

    /**
     * View files.
     */
    private function handleViews()
    {
        // Load the views...
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'canvas');

        // Allow publishing view files, with tag: views
        $this->publishes([
            __DIR__.'/../resources/views/auth' => base_path('resources/views/vendor/canvas/auth'),
            __DIR__.'/../resources/views/backend' => base_path('resources/views/vendor/canvas/backend'),
            __DIR__.'/../resources/views/errors' => base_path('resources/views/vendor/canvas/errors'),
            __DIR__.'/../resources/views/frontend' => base_path('resources/views/vendor/canvas/frontend'),
        ], 'views');
    }

    /**
     * Migration files.
     */
    private function handleMigrations()
    {
        // change default string length
        // @see https://github.com/laravel/framework/issues/17508
        Schema::defaultStringLength(191);

        // Load the migrations...
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    /**
     * Route files.
     */
    private function handleRoutes()
    {
        // Get the routes...
        require realpath(__DIR__.'/../routes/web.php');
    }

    /**
     * Command files.
     */
    private function handleCommands()
    {
        // Register the commands...
        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }
    }

    /**
     * Register factory files.
     *
     * @param  string  $path
     * @return void
     */
    protected function registerEloquentFactoriesFrom($path)
    {
        $this->app->make(EloquentFactory::class)->load($path);
    }

    /**
     * Register any Eloquent Model event observers.
     *
     * @return void
     */
    protected function registerEloquentObservers()
    {
        Settings::observe(SettingsObserver::class);
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->handleConfigs();
        $this->handleMigrations();
        $this->handleViews();
        $this->handleTranslations();
        $this->handleRoutes();
        $this->handleCommands();
        $this->handleAssets();
        $this->registerEloquentObservers();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $loader = AliasLoader::getInstance();
        $router = $this->app['router'];

        // Register factories...
        $this->registerEloquentFactoriesFrom(__DIR__.'/../database/factories');

        // Register service providers...
        $this->app->register(ScoutServiceProvider::class);
        $this->app->register(ExcelServiceProvider::class);
        $this->app->register(MediaManagerServiceProvider::class);
        $this->app->register(TNTSearchScoutServiceProvider::class);
        $this->app->register(ExtensionsServiceProvider::class);

        // Register facades...
        $loader->alias('Excel', Excel::class);
        $loader->alias('Settings', Settings::class);
        $loader->alias('CanvasSetup', SetupHelper::class);
        $loader->alias('CanvasRoute', RouteHelper::class);
        $loader->alias('CanvasHelper', CanvasHelper::class);
        $loader->alias('CanvasConfig', ConfigHelper::class);
        $loader->alias('ConfigWriter', ConfigWriter::class);

        // Register middleware...
        $router->aliasMiddleware('checkIfAdmin', CheckIfAdmin::class);
        $router->aliasMiddleware('canvasInstalled', EnsureInstalled::class);
        $router->aliasMiddleware('canvasNotInstalled', EnsureNotInstalled::class);
        $router->aliasMiddleware('checkForMaintenanceMode', CheckForMaintenanceMode::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
