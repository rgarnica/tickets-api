<?php

namespace App\Services;

use App\Support\TicketAnalyser;
use App\Repositories\TicketRepositoryInterface as TicketRepository;

class TicketClassificator
{

    protected $repo;
    protected $algorithms;

    public function __construct(TicketRepository $repo) {
        $this->repo = $repo;
        
    }

    public function setAlgorithms(TicketAnalyser ... $algorithms)
    {
        $this->algorithms = $algorithms;
    }

    public function run()
    {
        $tickets = $this->repo->get();

        $classified = $tickets->map(function($ticket) {
            $points = 0;
            foreach ($this->algorithms as $algorithm) {
                $points += $algorithm->run($ticket);
            }
            
            $ticket["PriorityPoints"] = $points;
            $ticket["PriorityLabel"] = ($points > 0.50) ? "Alta" : "Normal"; 

            return $ticket;
        });

        $this->repo->save($classified);
    }

}