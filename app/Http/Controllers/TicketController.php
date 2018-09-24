<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\TicketCollection;
use App\Repositories\TicketRepositoryInterface as TicketRepository;

class TicketController extends Controller
{

    public function index(Request $request, TicketRepository $ticketsRepo)
    {

        $this->validate($request, [
            'date_create_start' => 'required_with:date_create_end|date',
            'date_create_end' => 'required_with:date_create_start|date'
        ]);

        $tickets = $ticketsRepo->filter(
                $request->only('date_create_start', 'date_create_end', 'priority_label')
            )
            ->order($request->input('order_by'))
            ->get()
            ->paginate(5, $request->input('page'))
            ->setPath($request->fullUrl());
        
        return new TicketCollection($tickets);
    }

}
