<?php

use App\Repositories\TicketJsonRepository;


class TicketRepositoryTest extends TestCase
{

    private function makeTicketRepository()
    {
        $jsonFile = file_get_contents(
            storage_path('tests/tickets-test.json')
        );
        $json = json_decode($jsonFile, true);

        return new TicketJsonRepository(
            collect($json),
            storage_path('tests/tickets-test.json')
        );
    }

    
    public function testCanFilterTicketsByDatePeriod()
    {
        
        $repo = $this->makeTicketRepository();

        $filters = [
            'date_create_start' => '2017-12-13',
            'date_create_end' => '2017-12-13'
        ];

        $tickets = $repo->filter($filters)->get();

        $this->assertCount(1, $tickets);

    }

    public function testCanGetAllTickets()
    {
        $repo = $this->makeTicketRepository();
        $tickets = $repo->get();
        $this->assertCount(2, $tickets);
    }

    public function testCanGetOrderedByDate()
    {
        $repo = $this->makeTicketRepository();
        $tickets = $repo->order('date_create.desc')->get();
        $this->assertEquals(28891, $tickets[0]['TicketID']);
    }

}
