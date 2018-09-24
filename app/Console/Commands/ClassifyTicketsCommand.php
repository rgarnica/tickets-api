<?php

namespace App\Console\Commands;

use Phpml\Dataset\CsvDataset;
use Illuminate\Console\Command;
use App\Support\TicketDateAnalyser;
use Phpml\Classification\NaiveBayes;
use App\Services\TicketClassificator;
use Phpml\Tokenization\WordTokenizer;
use App\Support\TicketSentimentAnalyser;
use App\Support\SentimentAnalyserInterface;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use App\Repositories\TicketRepositoryInterface as TicketRepository;

class ClassifyTicketsCommand extends Command
{

    protected $signature = 'tickets:classify
                            {dataset-path : Path to training dataset}
                           ';

    protected $description = "Classify tickets priority between Neutral and High priority";


    public function handle(
        TicketClassificator $classificator,
        SentimentAnalyserInterface $analyser
    ) {
        $pathToDataset = $this->argument('dataset-path');

        $this->line("Training the classificator");
        $dataset = new CsvDataset(storage_path($pathToDataset), 1);
        $analyser->train($dataset);

        $algosDate = new TicketDateAnalyser(0.20);
        $algosSentiment = new TicketSentimentAnalyser($analyser, 0.80);

        $classificator->setAlgorithms($algosDate, $algosSentiment);

        $classificator->run();
        $this->info("Tickets classified successfully!");
        
    }

}