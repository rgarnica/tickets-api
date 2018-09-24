<?php

namespace App\Providers;

use Phpml\ModelManager;
use Phpml\Dataset\CsvDataset;
use App\Support\SentimentAnalyser;
use Illuminate\Support\Collection;
use Phpml\Classification\NaiveBayes;
use Phpml\Tokenization\WordTokenizer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use App\Repositories\TicketJsonRepository;
use App\Services\SentimentAnalyserTrainer;
use App\Support\SentimentAnalyserInterface;
use Phpml\FeatureExtraction\TfIdfTransformer;
use App\Repositories\TicketRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use App\Services\SentimentAnalyserTrainerInterface;

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
                    json_decode(Storage::get(config('app.tickets_json')), true)
                ),
                storage_path(config('app.tickets_json'))
            );
        });

        $this->app->bind(SentimentAnalyserInterface::class, function() {
            return new SentimentAnalyser(
                new TokenCountVectorizer(new WordTokenizer()),
                new TfIdfTransformer(),
                new NaiveBayes()
            );
        });
    }
}
