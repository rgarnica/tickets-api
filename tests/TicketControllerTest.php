<?php

class TicketControllerTest extends TestCase
{

   
    public function testUserCanGetTicketsPaginated()
    {
    
        $this->json('GET', '/api/v1/public/tickets', [])
            ->seeJsonStructure([
                'data',
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'path',
                    'per_page',
                    'to',
                    'total'
                ],
                'links'
            ]);

    }

}
