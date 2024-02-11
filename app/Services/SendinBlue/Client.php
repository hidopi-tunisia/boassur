<?php

namespace App\Services\SendinBlue;

use App\Models\Commande;
use Exception;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Storage;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;
use SendinBlue\Client\Model\SendSmtpEmailAttachment;
use SendinBlue\Client\Model\SendSmtpEmailReplyTo;
use SendinBlue\Client\Model\SendSmtpEmailSender;

class Client
{
    public function __construct(
        protected string $key,
    ) {
    }

    public function send(Commande $commande)
    {
        $commande->loadMissing(['site', 'voyageur', 'reservation', 'contrat']);

        $destinataire = env('MAIL_TESTER');
        if (env('APP_ENV') === 'production') {
            $destinataire = $commande->voyageur->email;
        }

        $attachements = [];
        if (empty($commande->reservation->attestation) === false) {
            $content = chunk_split(base64_encode(file_get_contents($commande->reservation->attestation)));
            $attachements[] = new SendSmtpEmailAttachment([
                'content' => $content,
                'name' => 'Attestation.pdf',
            ]);
        }

        if (empty($commande->reservation->url) === false) {
            $content = chunk_split(base64_encode(file_get_contents($commande->reservation->url)));
            $attachements[] = new SendSmtpEmailAttachment([
                'content' => $content,
                'name' => 'Recap-'.$commande->reservation->num_souscription.'.pdf',
            ]);
        }

        if ($commande->contrat !== null && empty($commande->contrat->url_cgv) === false && Storage::exists($commande->contrat->url_cgv) === true) {
            $content = chunk_split(base64_encode(Storage::get($commande->contrat->url_cgv)));
            $attachements[] = new SendSmtpEmailAttachment([
                'content' => $content,
                'name' => 'Conditions-Generales.pdf',
            ]);
        }

        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $this->key);
        $apiInstance = new TransactionalEmailsApi(
            new GuzzleClient(),
            $config
        );

        $sendSmtpEmail = new SendSmtpEmail();
        $sendSmtpEmail['subject'] = $commande->site->objet_email;
        $sendSmtpEmail['sender'] = new SendSmtpEmailSender(['name' => $commande->site->sender, 'email' => $commande->site->email]);
        $sendSmtpEmail['to'] = [[
            'name' => $commande->voyageur->full_name,
            'email' => $destinataire,
        ]];
        $sendSmtpEmail['replyTo'] = new SendSmtpEmailReplyTo(['email' => $commande->site->email]);
        $sendSmtpEmail['headers'] = ['Order-Id' => $commande->hashid];
        $sendSmtpEmail['htmlContent'] = '<html><body>'.htmlspecialchars_decode($commande->site->contenu_email).'</body></html>';

        if (count($attachements) > 0) {
            $sendSmtpEmail['attachment'] = $attachements;
        }

        try {
            return $apiInstance->sendTransacEmail($sendSmtpEmail);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
