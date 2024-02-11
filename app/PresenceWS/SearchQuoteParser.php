<?php

namespace App\PresenceWS;

use Exception;
use NumberFormatter;
use SimpleXMLElement;

class SearchQuoteParser implements ParserInterface
{
    public function parse(SimpleXMLElement $xml)
    {
        // Vérifier si on a des erreurs
        if ((string) $xml->Action->Status['Severity'] === 'Error') {
            return ['success' => false, 'erreurs' => [(string) $xml->Action->Status]];
        }

        $formatter = new NumberFormatter('fr-FR', NumberFormatter::CURRENCY);

        $disponibles = [];

        try {
            // Récupérer le CGV
            foreach ($xml->Segments->Segment as $segment) {
                if ((string) $segment['Status'] === 'Available' && (string) $segment['Is'] === 'Insurance') {
                    foreach ($segment->Descriptions->Description as $desc) {
                        if ((string) $desc['Role'] === 'Media' && (string) $desc['Name'] === 'GeneralTermsOfSales') {
                            $disponibles[(string) $segment['Ref']] = (string) $desc->URL;
                            break;
                        }
                    }
                }
            }

            $assurances = [];

            // Récupérer les autres infos
            foreach ($xml->Bookings->Booking as $booking) {
                $ref = (string) $booking['Ref'];

                // On ne prend que les assurances available dans segments
                if (array_key_exists($ref, $disponibles)) {
                    $assurances[] = [
                        'ref' => $ref,
                        'priceline' => (string) $booking->Code['Value'],
                        'nom' => (string) $booking['Name'],
                        'prix' => (int) $booking->Price['Value'],
                        'prix_eur' => $formatter->format((int) $booking->Price['Value'] / 100),
                        'cgv' => $disponibles[$ref],
                    ];
                }
            }
        } catch (Exception $e) {
            return ['success' => false, 'erreurs' => ['Une erreur est survenue']];
        }

        return ['success' => true, 'body' => $assurances];
    }
}
