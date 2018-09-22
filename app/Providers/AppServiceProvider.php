<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use App\Repositories\TicketJsonRepository;
use App\Repositories\TicketRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class AppServiceProvider extends ServiceProvider
{

    public function boot()
    {
        Collection::macro('paginate', function($perPage, $page = null, $total = null,  $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TicketRepositoryInterface::class, function () {
            return new TicketJsonRepository(
                Collection::make(
                    json_decode(Storage::disk('local')->get('tickets.json'), true)
                )
            );
        });
    }
}
