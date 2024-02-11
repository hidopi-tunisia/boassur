<?php

namespace App\Console\Commands;

use App\Mail\ConfirmationEmailNotDelivered;
use App\Models\Commande;
use App\Services\SendinBlue\Client as NotificationClient;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class Notifier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifier';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = $this->ask('ID please?');

        $commande = Commande::with(['reservation', 'site', 'voyageur', 'contrat'])->where('id', '=', $id)->first();

        if ($commande->reservation) {
            $client = resolve(NotificationClient::class);

            try {
                $res = $client->send($commande);

                $commande->email_id = $res->getMessageId();
                $commande->save();
                dd($commande->email_id);
            } catch (Exception $e) {
                Mail::to($commande->site->notifier)
                    ->send(new ConfirmationEmailNotDelivered($commande));
                echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: '.$e->getMessage().PHP_EOL;
            }
        } else {
            print_r('Commande sans resa');
        }

        return 0;
    }
}
