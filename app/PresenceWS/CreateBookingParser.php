<?php

namespace App\PresenceWS;

use SimpleXMLElement;

class CreateBookingParser implements ParserInterface
{
    public function parse(SimpleXMLElement $xml)
    {
        // Vérifier si on a des erreurs
        if ($xml->Action->Status['Severity'] === 'Error') {
            return ['success' => false, 'erreur' => $xml->Action->Status['Code']];
        }

        $resa = [];

        // Récupérer le contrat
        foreach ($xml->Segments->Descriptions->Description as $desc) {
            if ((string) $desc['Role'] === 'Summary' && (string) $desc['Name'] === 'Recap') {
                $resa['url'] = (string) $desc->URL;
                break;
            }
        }

        // Récupérer l'attestation
        foreach ($xml->Segments->Descriptions->CustomField as $desc) {
            if ((string) $desc['Name'] === 'Attestation') {
                $resa['attestation'] = (string) $desc->Description->URL;
                break;
            }
        }

        // Récupérer le CGV
        foreach ($xml->Segments->Segment as $segment) {
            if ((string) $segment['Status'] === 'Available' && (string) $segment['Is'] === 'Insurance') {
                foreach ($segment->Descriptions->Description as $desc) {
                    if ((string) $desc['Role'] === 'Media' && (string) $desc['Name'] === 'GeneralTermsOfSales') {
                        $resa['cgv'] = (string) $desc->URL;
                        break;
                    }
                }
            }
        }

        // Les infos sur le contrat
        $codes = [];
        foreach ($xml->Contract->Codes->Code as $code) {
            $codes[(string) $code['Name']] = (string) $code['Value'];
        }
        $resa['codes'] = $codes;
        $resa['num_souscription'] = $codes['SouscriptionNumber'];
        $resa['ref_souscription'] = $codes['SubscriptionReference'];
        $resa['date_souscription'] = $codes['BookingCreationDate'];

        // Les infos sur la resa
        $resa['statut'] = (string) $xml->Bookings->Booking['Status'];

        return ['success' => true, 'body' => $resa];
    }
}
