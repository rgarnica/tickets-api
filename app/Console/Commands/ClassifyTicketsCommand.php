<?php

namespace App\Console\Commands;

use Phpml\Dataset\CsvDataset;
use Illuminate\Console\Command;
use App\Services\SentimentAnalyser;
use App\Services\TicketDateAnalyser;
use Phpml\Classification\NaiveBayes;
use Phpml\Tokenization\WordTokenizer;
use App\Services\TicketSentimentAnalyser;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use App\Repositories\TicketRepositoryInterface as TicketRepository;

class ClassifyTicketsCommand extends Command
{

    protected $signature = 'tickets:classify
                            {dataset_path : Path to training dataset}
                           ';

    protected $description = "Classify tickets priority between Neutral and High priority";


    public function handle(TicketRepository $ticketsRepo)
    {
        $pathToDataset = $this->argument('dataset_path');

        $this->line("Getting all tickets");
        $tickets = $ticketsRepo->get();
        $this->line("Creating classification parameters");
        $dataset = new CsvDataset(storage_path($pathToDataset), 1);
        $vectorizer = new TokenCountVectorizer(new WordTokenizer());
        $tfIdfTransformer = new TfIdfTransformer();
        $classifier = new NaiveBayes();
        $analyser = new SentimentAnalyser($vectorizer, $tfIdfTransformer, $classifier);


        $this->line("Training the classificator");
        $analyser->train($dataset);

        $this->line("Classifiyng tickets...");

        $tickets = $tickets->map(function($ticket) use ($analyser) {
            $an = [];
            $an[] = new TicketDateAnalyser($ticket, 0.20);
            $an[] = new TicketSentimentAnalyser($analyser, $ticket, 0.80);
            $points = 0;
            foreach ($an as $classification) {
                $points += $classification->run();
            }
            
            $ticket["PriorityPoints"] = $points;
            $ticket["PriorityLabel"] = ($points > 0.50) ? "Alta" : "Normal"; 
            return $ticket;
        });

        $ticketsRepo->save($tickets);
        $this->info("Tickets classified successfully!");
        
    }

}