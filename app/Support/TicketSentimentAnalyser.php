<?php

namespace App\Support;

class TicketSentimentAnalyser extends TicketAnalyser
{

    protected $classifier;

    const NEGATIVE_WEIGHT = 0.40;

    public function __construct(SentimentAnalyserInterface $classifier, float $weight)
    {
        parent::__construct($weight);
        $this->classifier = $classifier;
    }

    private function getCostumerInteractions($ticket)
    {
        return array_filter($ticket["Interactions"], function($item) {
            return $item["Sender"] === "Customer";
        });
    }

    private function getInteractionsSentences(array $interactions)
    {
        return array_map(function($item){
            return $item["Subject"] . ". " .  $item["Message"];
        }, $interactions);
    }

    public function run(array $ticket)
    {
        $results = [];
        $interactions = $this->getCostumerInteractions($ticket);
        $sentences = $this->getInteractionsSentences($interactions);
        $result = $this->classifier->predict($sentences);

        $negatives = array_filter($result, function($item) {
            return $item === 'negative';
        });

        return count($negatives) * self::NEGATIVE_WEIGHT * $this->weight;
    }

}