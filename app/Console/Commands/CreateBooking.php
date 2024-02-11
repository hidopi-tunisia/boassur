<?php

namespace App\Console\Commands;

use App\Models\Commande;
use App\PresenceWS\PresenceQuery;
use Illuminate\Console\Command;

class CreateBooking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $query;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->query = new PresenceQuery('CreateBooking');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = $this->ask('ID please?');

        $commande = Commande::where('id', '=', $id)->get()->first();
        $res = $this->query->request($commande->getCreateBookingParams());

        if ($res['success'] === true) {
            // Enregistrer les infos CreateBooking
            $commande->reservation()->create([
                'url' => $res['body']['url'],
                'attestation' => $res['body']['attestation'],
                'cgv' => $res['body']['cgv'],
                'codes' => serialize($res['body']['codes']),
                'num_souscription' => $res['body']['num_souscription'],
                'ref_souscription' => $res['body']['ref_souscription'],
                'date_souscription' => $res['body']['date_souscription'],
                'statut' => $res['body']['statut'],
            ]);
        }

        return 0;
    }
}
