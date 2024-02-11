<?php

namespace App\PresenceWS;

interface FormatterInterface
{
    public function validate(array $inputs);

    public function format(array $inputs);
}
