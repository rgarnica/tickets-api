<?php

use Phpml\Dataset\CsvDataset;
use App\Services\SentimentAnalyser;
use App\Services\TicketDateAnalyser;
use Phpml\Classification\NaiveBayes;
use Phpml\Tokenization\WordTokenizer;
use App\Services\TicketSentimentAnalyser;
use App\Repositories\TicketJsonRepository;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\FeatureExtraction\TokenCountVectorizer;

class TicketSentimentAnalyserTest extends TestCase
{

    private function makeTicketRepository()
    {
        $jsonFile = file_get_contents(
            storage_path('tests/tickets-test.json')
        );
        $json = json_decode($jsonFile, true);

        return new TicketJsonRepository(
            collect($json),
            storage_path('tests/tickets-test.json')
        );
    }

    public function testCanReturnSentimentFromTickets()
    {
        $repo = $this->makeTicketRepository();
        $ticket = $repo->get()[0];

        $classifier = new SentimentAnalyser(
            new TokenCountVectorizer(new WordTokenizer()),
            new TfIdfTransformer(),
            new NaiveBayes()
        );

        $classifier->train(
            new CsvDataset(storage_path('app/sentences.csv'), 1, true)
        );

        $an = new TicketSentimentAnalyser(
            $classifier,
            $ticket,
            1
        );

        $result = $an->run();

        $this->assertTrue(is_float($result));        
    }

}