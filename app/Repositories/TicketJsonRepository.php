<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class TicketJsonRepository implements TicketRepositoryInterface
{

    private $tickets;

    public function __construct(Collection $tickets) 
    {
        $this->tickets = $tickets;
    }

    public function get() : Collection
    {
        return $this->tickets;
    }

    public function filter(?array $filters) : TicketRepositoryInterface
    {
        if (isset($filters['date_create_start'])) {

            $this->applyFilterByDatePeriod(
                $filters['date_create_start'], 
                $filters['date_create_end']
            );
            
        }

        return $this;
    }

    public function order(?string $orderBy) : TicketRepositoryInterface
    {
        if (!is_null($orderBy)) {
            $this->parseOrderBy($orderBy);
        }

        return $this;
    }


    private function applyFilterByDatePeriod(string $startDate, string $endDate)
    {
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->startOfDay();

        $this->tickets = $this->tickets->filter(function($item) use ($startDate, $endDate) {
            $date = Carbon::parse($item['DateCreate'])->startOfDay();
            return $date >= $startDate && $date <= $endDate;
        })->values();
    }

    private function parseOrderBy(string $orderBy) : void
    {
        [$field, $method] = explode('.', $orderBy);

        $collectionMethod = ($method === 'asc') ? 'sortBy' : 'sortByDesc';

        if ($field === 'date_create') {
            
            $this->orderByDate($collectionMethod);
            
        } else {

            $this->tickets = 
                $this->tickets->$collectionMethod($field)->values();
            
        }
    }

    private function orderByDate(string $method) : void
    {
        $this->tickets = $this->tickets->$method(function($item, $key){
            return Carbon::parse($item['DateCreate'])->getTimestamp();
        })->values();
    }


}