<?php

namespace App\Support;

use Carbon\Carbon;

class TicketDateAnalyser extends TicketAnalyser
{

    

    public function run(array $ticket)
    {
        
        $dateCreate = Carbon::parse($ticket['DateCreate']);
        $dateUpdate = Carbon::parse($ticket['DateUpdate']);

        return ($dateUpdate->diffInDays($dateCreate) / 100) * $this->weight;

    }

}