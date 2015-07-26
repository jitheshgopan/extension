<?php namespace Orchestra\Extension\Jobs;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Foundation\Application;

class RefreshRouteCache extends Job implements SelfHandling
{
    /**
     * Execute the job.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Contracts\Console\Kernel  $kernel
     *
     * @return void
     */
    public function handle(Application $app, Kernel $kernel)
    {
        if (! $app->routesAreCached()) {
            return;
        }

        $app->terminating(function () use ($kernel) {
            $kernel->call('route:cache');
        });
    }
}
