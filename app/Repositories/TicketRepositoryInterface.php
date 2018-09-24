<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use App\Repositories\TicketsRepositoryInterface;

interface TicketRepositoryInterface 
{

    public function get();
    public function filter(?array $filters) : TicketRepositoryInterface;
    public function order(?string $orderBy) : TicketRepositoryInterface;
    public function save(Collection $tickets);

}