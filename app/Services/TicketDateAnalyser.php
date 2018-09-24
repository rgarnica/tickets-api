<?php

namespace App\Services;

use Carbon\Carbon;

class TicketDateAnalyser extends TicketAnalyser
{

    

    public function run()
    {
        
        $dateCreate = Carbon::parse($this->ticket['DateCreate']);
        $dateUpdate = Carbon::parse($this->ticket['DateUpdate']);

        return ($dateUpdate->diffInDays($dateCreate) / 100) * $this->weight;

    }

}