<?php

namespace Pharaonic\Laravel\Sluggable;

use Closure;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class SluggableServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/sluggable.php',
            'pharaonic.sluggable'
        );

        $this->blueprintMacro('sluggable', function (string $column = 'slug') {
            return $this->string($column)->nullable()->unique();
        });
    }

    /**
     * Register a Blueprint macro; Laravel binds the closure to the Blueprint instance.
     *
     * @param-closure-this Blueprint $macro
     */
    private function blueprintMacro(string $name, Closure $macro): void
    {
        Blueprint::macro($name, $macro);
    }

    public function boot(): void
    {
        Blade::directive('slug', function ($expression) {
            return "<?php echo slug($expression); ?>";
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/sluggable.php' => $this->app->configPath('pharaonic/sluggable.php'),
            ], ['pharaonic', 'laravel-sluggable', 'pharaonic-config', 'sluggable-config']);
        }
    }
}
