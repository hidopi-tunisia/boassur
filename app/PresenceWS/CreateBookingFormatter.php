<?php

namespace App\PresenceWS;

class CreateBookingFormatter implements FormatterInterface
{
    use FormatNodeTrait;

    public function validate($inputs)
    {
        return true;
    }

    /**
     * Requête CreateBooking
     */
    public function format($inputs)
    {
        $this->validate($inputs);

        // Former le xml corps de la requête
        $xml = '&lt;Action Purpose="Create" Code="Booking" /&gt;';
        $xml .= $this->segments($inputs);
        $xml .= $this->travellers($inputs['voyageur'], $inputs['accompagnants']);
        $xml .= $this->_contract($inputs['contrat']);
        $xml .= $this->trip($inputs);

        return $xml;
    }

    private function _contract($inputs)
    {
        $comments = $inputs['commande_id'] ?? '';

        $xml = '&lt;Contract Status="Request"&gt;';
        $xml .= '&lt;Codes&gt;';
        $xml .= '&lt;Code Value="'.$inputs['quote_id'].'" Name="ContractID"/&gt;';
        $xml .= '&lt;Code Value="'.$inputs['quote_priceline'].'" Name="ContractPriceLine"/&gt;';
        $xml .= '&lt;/Codes&gt;';
        $xml .= '&lt;Descriptions&gt;';
        $xml .= '&lt;Description Role="Comment"&gt;&lt;![CDATA['.$comments.']]&gt;&lt;/Description&gt;';
        $xml .= '&lt;/Descriptions&gt;';
        $xml .= '&lt;/Contract&gt;';

        return $xml;
    }
}
