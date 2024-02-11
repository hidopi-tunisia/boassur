<?php

namespace App\PresenceWS;

use Carbon\Carbon;
use nusoap_client;

class PresenceQuery
{
    private $formatter = null;

    private $parser = null;

    private $operation = null;

    //bookList is not instantiated at construct time
    public function __construct($operation)
    {
        switch ($operation) {
            case 'SearchQuote':
                $this->operation = $operation;
                $this->formatter = new SearchQuoteFormatter();
                $this->parser = new SearchQuoteParser();
                break;
            case 'CreateBooking':
                $this->operation = $operation;
                $this->formatter = new CreateBookingFormatter();
                $this->parser = new CreateBookingParser();
                break;
        }
    }

    public function request($inputs)
    {
        $client = new nusoap_client('https://wspresence.viaxeoassur.com/ws/ws3?WSDL', 'wsdl');
        $client->soap_defencoding = 'UTF-8';
        $client->decode_utf8 = false;
        $client->operation = $this->operation;

        $xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ws3="ws3">';
        $xml .= '<soapenv:Header />';
        $xml .= '<soapenv:Body>';
        $xml .= '<ws3:'.$this->operation.'>';
        $xml .= '<ws3:Command>';
        $xml .= '&lt;Transaction xmlns="http://www.exchangefortravel.org/xml/current" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="TransactionRequestType" Version="WS3.1_O5a" TimeStamp="'.Carbon::now()->toRfc3339String().'"&gt;';
        $xml .= '&lt;Control&gt;';
        $xml .= '&lt;Requester Pwd="'.env('WS_PASSWORD').'" Uid="'.env('WS_UID').'" Code="PARTNER" Channel="Extranet"&gt;';
        $xml .= '&lt;Agency&gt;';
        $xml .= '&lt;Requester Owner="Phil" Pwd="'.env('WS_PASSWORD').'" Uid="'.env('WS_UID').'" Code="SAPEIG" /&gt;';
        $xml .= '&lt;/Agency&gt;';
        $xml .= '&lt;/Requester&gt;';
        $xml .= '&lt;/Control&gt;';
        $xml .= $this->formatter->format($inputs);
        $xml .= '&lt;/Transaction&gt;';
        $xml .= '</ws3:Command>';
        $xml .= '</ws3:'.$this->operation.'>';
        $xml .= '</soapenv:Body>';
        $xml .= '</soapenv:Envelope>';
        $response = $this->parse($client->send($xml));

        if (array_key_first($response) === 'erreurs') {
            return $response;
        }

        return $this->parser->parse($response['body']);
    }

    public function parse($response)
    {
        if (is_array($response)) {
            // Parser la réponse correspondant à l'opération
            $key = $this->operation.'Result';
            if (array_key_exists($key, $response)) {
                libxml_use_internal_errors(true);

                $xml = simplexml_load_string($response[$key]);

                // Retourner les erreurs si la réponse contient des erreurs xml
                if ($xml === false) {
                    $erreurs = [];
                    foreach (libxml_get_errors() as $erreur) {
                        $erreurs[] = $erreur->message;
                    }

                    return compact('erreurs');
                }

                return ['body' => $xml];
            }

            // Reporter l'erreur
            if (array_key_first($response) === 'faultcode') {
                return ['erreurs' => array_values($response)];
            }
        }

        return ['erreurs' => ['Une erreur est survenue']];
    }
}
