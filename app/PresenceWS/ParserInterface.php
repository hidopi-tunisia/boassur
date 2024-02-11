<?php

namespace App\PresenceWS;

use SimpleXMLElement;

interface ParserInterface
{
    public function parse(SimpleXMLElement $xml);
}
