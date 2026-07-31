<?php

namespace App\Services\FormCalendar;

class ReserveCheckResult
{
    public function __construct(
        public bool $result,
        public string $message = ''
    ) {}
}
