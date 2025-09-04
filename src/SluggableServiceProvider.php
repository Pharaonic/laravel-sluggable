<?php

namespace Pharaonic\Laravel\Sluggable;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class SluggableServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sluggable.php', 'pharaonic.sluggable');

        Blueprint::macro('sluggable', fn () => $this->string('slug')->nullable()->index());
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            AboutCommand::add('Pharaonic', fn () => ['Sluggable' => '12.0.0']);

            $this->publishes(
                [__DIR__ . '/../config/sluggable.php' => config_path('pharaonic/sluggable.php')],
                ['pharaonic', 'sluggable', 'config']
            );
        }

        Blade::directive('slug', fn ($data) => "<?php echo slug($data); ?>");
    }
}
