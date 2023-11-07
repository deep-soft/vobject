<?php

namespace Sabre\VObject;

use PHPUnit\Framework\TestCase;

class InvalidValueParamTest extends TestCase
{
    public function testWorkaround()
    {
        $event = <<<ICS
            BEGIN:VCALENDAR
            VERSION:2.0
            BEGIN:VEVENT
            DTEND;TZID=Europe/Paris:20170530T220000
            DTSTAMP:20230317T130521Z
            DTSTART;TZID=Europe/Paris:20170530T200000
            LAST-MODIFIED:20230316T155811Z
            LOCATION;VALUE=ERROR:EXAMPLE
            SEQUENCE:0
            STATUS:CONFIRMED
            SUMMARY:AG MP3
            UID:0171706E-00F4-4846-8B5F-7FBD474A90AC
            END:VEVENT
            END:VCALENDAR
            ICS;

        $doc = Reader::read($event);
        $this->assertEquals("LOCATION:EXAMPLE\r\n", $doc->VEVENT->LOCATION->serialize());
    }

    public function testInvalidValue()
    {
        $event = <<<ICS
            BEGIN:VCALENDAR
            VERSION:2.0
            BEGIN:VEVENT
            DTEND;TZID=Europe/Paris:20170530T220000
            DTSTAMP:20230317T130521Z
            DTSTART;TZID=Europe/Paris:20170530T200000
            LAST-MODIFIED:20230316T155811Z
            LOCATION;VALUE=ROUTE DE BOULOGNE, 32220 LOMBEZ FRANCE:Route de Boulogne\,32220 Lombez\n France
            SEQUENCE:0
            STATUS:CONFIRMED
            SUMMARY:AG MP3
            UID:0171706E-00F4-4846-8B5F-7FBD474A90AC
            END:VEVENT
            END:VCALENDAR
            ICS;

        $doc = Reader::read($event);
        $this->assertEquals("LOCATION:Route de Boulogne\, \r\n32220 Lombez\n France\r\n", $doc->VEVENT->LOCATION->serialize());
    }
}
