<?php

namespace App\Support;

abstract class TicketAnalyser
{

    protected $weight;

    public function __construct(float $weight)
    {
        $this->weight = $weight;
    }

    public abstract function run(array $ticket);
}