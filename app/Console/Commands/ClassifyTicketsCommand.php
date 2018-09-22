<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\TicketsRepositoryInterface as TicketsRepository;

class ClassifyTicketsCommand extends Command
{

    protected $signature = "tickets:classify";

    protected $description = "Classify tickets priority between Neutral and High";


    public function handle(TicketsRepository $ticketsRepo)
    {
        $this->info("Getting all tickets");
        $tickets = $ticketsRepo->get();
    }

}