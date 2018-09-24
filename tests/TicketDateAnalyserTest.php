<?php

use App\Services\TicketDateAnalyser;

class TicketDateAnalyserTest extends TestCase
{

    private function makeTicketArray()
    {
        $ticket = [
            "TicketID" => 28890,
            "CategoryID" => 57526,
            "CustomerID" => 97979,
            "CustomerName" => "Cox Workman",
            "CustomerEmail" => "cox.workman@neoassist.com",
            "DateCreate" => "2017-12-13 03:08:42",
            "DateUpdate" => "2018-01-04 09:18:25",
        ];

        return $ticket;
    }

    public function testCanReturnDateImportanceRating()
    {
        $ticket = $this->makeTicketArray();
        $result = (new TicketDateAnalyser($ticket, 1))->run();
        $this->assertEquals(0.22, $result);
    }

}