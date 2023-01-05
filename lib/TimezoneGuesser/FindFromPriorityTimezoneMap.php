<?php

declare(strict_types=1);

namespace Sabre\VObject\TimezoneGuesser;

use DateTimeZone;

class FindFromPriorityTimezoneMap implements TimezoneFinder
{
    private $map = [];

    public function find(string $tzid, bool $failIfUncertain = false): ?DateTimeZone
    {
        $tzid = str_replace(".", "", $tzid);

        // Next, we check if the tzid is somewhere in our tzid map.
        if ($this->hasTzInMap($tzid)) {
            return new DateTimeZone($this->getTzFromMap($tzid));
        }

        return null;
    }

    private function getTzMaps(): array
    {
        if ([] === $this->map) {
            $map = array_merge(
                include __DIR__.'/../timezonedata/priorityzones.php',
            );
            $this->map = array_combine(
                array_map(static fn (string $key) => str_replace(".", "", mb_strtolower($key, 'UTF-8')), array_keys($map)),
                array_values($map),
            );
        }

        return $this->map;
    }

    private function getTzFromMap(string $tzid): string
    {
        return $this->getTzMaps()[mb_strtolower($tzid, 'UTF-8')];
    }

    private function hasTzInMap(string $tzid): bool
    {
        return isset($this->getTzMaps()[mb_strtolower($tzid, 'UTF-8')]);
    }
}
