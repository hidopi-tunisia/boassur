<?php

namespace App\PresenceWS;

use Carbon\Carbon;
use Illuminate\Support\Str;

trait FormatNodeTrait
{
    /**
     * Génère la balise Segments correspondant au type de voyage
     *
     * @param  array  $inputs
     * @return string
     */
    public function segments($inputs)
    {
        $isLuneDeMiel = array_key_exists('luneDeMiel', $inputs) && $inputs['luneDeMiel'] === 1 ? 'true' : 'false';

        $xml = '&lt;Segments What="List"&gt;';
        $xml .= '&lt;Segment xsi:type="SegmentProductType" Is="Tour"&gt;';
        $xml .= '&lt;Types&gt;';
        $xml .= '&lt;Type xsi:type="TypeSpecialOfferType" Code="Wedding" Apply="'.$isLuneDeMiel.'" /&gt;';
        $xml .= '&lt;/Types&gt;';
        $xml .= '&lt;Prices&gt;';
        $xml .= '&lt;Price Role="Insurance" Name="Accomodation" Apply="false" /&gt;';
        $xml .= '&lt;Price Role="Insurance" Name="Flight" Apply="false" /&gt;';
        $xml .= '&lt;Price Role="Insurance" Formula="Leisure" Name="ContratType" /&gt;';
        $xml .= '&lt;Price Role="Insurance" Name="AcceptExtensionPax" Apply="false" /&gt;';
        $xml .= '&lt;Price Role="Insurance" Name="AcceptExtensionDuration" Apply="false" /&gt;';
        $xml .= '&lt;Price Role="Insurance" Name="AcceptAddon" Apply="false" /&gt;';
        $xml .= '&lt;/Prices&gt;';
        $xml .= '&lt;/Segment&gt;';
        $xml .= '&lt;/Segments&gt;';

        return $xml;
    }

    /**
     * Retourne la balise Travellers
     *
     * @param  array  $voyageur      Les infos sur le voyageur
     * @param  array  $accompagnants  La liste des accompagnants
     * @return string
     */
    public function travellers($voyageur, $accompagnants)
    {
        $numVoyageurs = 1 + count($accompagnants);

        $xml = '&lt;Travellers&gt;';
        $xml .= '&lt;Travellers Type="Person" Quantity="'.$numVoyageurs.'" /&gt;';
        $xml .= $this->_traveller($voyageur, 0);

        for ($i = 0; $i < count($accompagnants); $i++) {
            $xml .= $this->_traveller($accompagnants[$i], $i + 1);
        }

        $xml .= '&lt;/Travellers&gt;';

        return $xml;
    }

    /**
     * Retourne la balise Trip correspondant au voyage
     *
     * @param  array  $inputs
     * @return string
     */
    public function trip($inputs)
    {
        $reference = array_key_exists('ref', $inputs) ? $inputs['ref'] : Str::random(9);

        $xml = '&lt;Trip&gt;';
        $xml .= '&lt;Code Role="Reference" Value="'.$reference.'" /&gt;';
        $xml .= '&lt;Price Target="Total" Role="Total" Currency="EUR" Value="'.$inputs['prix_voyage'].'" /&gt;';
        $xml .= '&lt;Begin Value="'.$inputs['date_depart'].'" /&gt;';
        $xml .= '&lt;End Value="'.$inputs['date_retour'].'" /&gt;';
        $xml .= '&lt;From&gt;';
        $xml .= '&lt;Country&gt;';
        $xml .= '&lt;Code Value="FR" Owner="ISO_3166-1-Alpha2" /&gt;';
        $xml .= '&lt;/Country&gt;';
        $xml .= '&lt;/From&gt;';
        $xml .= '&lt;To&gt;';
        $xml .= '&lt;Country&gt;';
        $xml .= '&lt;Code Value="'.$inputs['code_pays_destination'].'" Owner="ISO_3166-1-Alpha2" /&gt;';
        $xml .= '&lt;/Country&gt;';
        $xml .= '&lt;/To&gt;';
        $xml .= '&lt;/Trip&gt;';

        return $xml;
    }

    /**
     * Retourne une balise Traveller
     *
     * @param  array  $inputs  Infos sur un voyageur
     * @param  int  $key     Le rang du voyageur
     * @return string
     */
    private function _traveller($inputs, $key)
    {
        $nom = $inputs['nom'].'/'.$inputs['prenom'];
        $type = 'Adult';
        $naissance = new Carbon($inputs['date_naissance']);
        $age = $naissance->diffInYears(Carbon::now());

        if ($age <= 12) {
            $type = 'Child';
        }
        if ($age <= 2) {
            $type = 'Infant';
        }

        $xml = '&lt;Traveller BirthDate="'.$inputs['date_naissance'].'" Type="'.$type.'" ID="T'.$key.'" Name="'.$nom.'"&gt;';
        if (array_key_exists('prix', $inputs)) {
            $xml .= '&lt;Price Name="PaxPrice" Value="'.$inputs['prix'].'" Currency="EUR" /&gt;';
        }
        if (! empty($inputs['telephone'])) {
            $xml .= '&lt;Telephone&gt;'.$inputs['telephone'].'&lt;/Telephone&gt;';
        }
        if (! empty($inputs['email'])) {
            $xml .= '&lt;Email&gt;'.$inputs['email'].'&lt;/Email&gt;';
        } else {
            $xml .= '&lt;Email /&gt;';
        }
        $xml .= '&lt;/Traveller&gt;';

        return $xml;
    }
}
