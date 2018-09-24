<?php

namespace App\Services;

abstract class TicketAnalyser
{

    protected $ticket;
    protected $weight;

    public function __construct(array $ticket, float $weight)
    {
        $this->ticket = $ticket;
        $this->weight = $weight;
    }

    public abstract function run();
}