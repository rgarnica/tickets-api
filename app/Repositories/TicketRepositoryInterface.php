<?php

namespace App\Repositories;

use App\Repositories\TicketsRepositoryInterface;

interface TicketRepositoryInterface 
{

    public function get();
    public function filter(?array $filters) : TicketRepositoryInterface;
    public function order(?string $orderBy) : TicketRepositoryInterface;

}