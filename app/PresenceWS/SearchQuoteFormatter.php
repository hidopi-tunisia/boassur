<?php

namespace App\PresenceWS;

class SearchQuoteFormatter implements FormatterInterface
{
    use FormatNodeTrait;

    public function validate($inputs)
    {
        return true;
    }

    /**
     * Requête SearchQuote
     */
    public function format($inputs)
    {
        $this->validate($inputs);

        // Former le xml corps de la requête
        $xml = '&lt;Action Purpose="Search" Code="Quote" /&gt;';
        $xml .= $this->segments($inputs);
        $xml .= $this->travellers($inputs['voyageur'], $inputs['accompagnants']);
        $xml .= $this->trip($inputs);

        return $xml;
    }
}
