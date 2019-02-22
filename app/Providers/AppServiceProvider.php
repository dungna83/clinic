<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Events\QueryExecuted;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
        $this->logAllQueries();
    }

    private function logAllQueries()
    {
        if ( !App::environment('prod') ){
            Event::listen(
                QueryExecuted::class,
                function (QueryExecuted $query) {
                    // Format binding data for sql insertion
                    foreach ($query->bindings as $i => $binding) {
                        if ($binding instanceof \DateTime) {
                            $query->bindings[ $i ] = $binding->format('\'Y-m-d H:i:s\'');
                        } else {
                            if (is_string($binding)) {
                                $query->bindings[ $i ] = "'$binding'";
                            }
                        }
                    }
                    // Insert bindings into query
                    $boundSql = str_replace(['%', '?'], ['%%', '%s'], $query->sql);
                    $boundSql = vsprintf($boundSql, $query->bindings);
                    Log::debug(
                        "QUERY: $boundSql;\n" .
                        "                                 TIME - {$query->time}ms"
                    );
                }
            );
        }
    }
}
