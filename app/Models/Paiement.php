<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = ['currency', 'amount', 'PM', 'ACCEPTANCE', 'STATUS', 'CARDNO', 'ED', 'CN', 'TRXDATE', 'PAYID', 'PAYIDSUB', 'NCERROR', 'BRAND', 'IPCTY', 'CCCTY', 'ECI', 'CVCCheck', 'AAVCheck', 'VC', 'IP'];

    protected $statuts = [
        0 => 'Incomplet ou invalide',
        1 => 'Annulé par client',
        2 => 'Autorisation refusée',
        4 => 'Commande encodée',
        40 => 'Stored waiting external result',
        41 => 'Attente paiement par client',
        46 => "Attente de l'authentification",
        5 => 'Autorisé',
        50 => 'Authorized waiting external result',
        51 => 'Autorisation en attente',
        52 => 'Autorisation incertaine',
        55 => 'En suspens',
        56 => 'OK avec paiements planifiés',
        57 => 'Erreur dans les paiements planifiés',
        59 => 'Autor. à obtenir manuellement',
        6 => 'Autorisé et annulé',
        61 => "Annul. d'autor. en attente",
        62 => "Annul. d'autor. incertaine",
        63 => "Annul. d'autor. refusée",
        64 => 'Autorisé et annulé',
        7 => 'Paiement annulé',
        71 => 'Annulation paiement en attente',
        72 => 'Annul paiement incertaine',
        73 => 'Annul paiement refusée',
        74 => 'Paiement annulé',
        75 => 'Annulation traitée par le marchand',
        8 => 'Remboursement',
        81 => 'Remboursement en attente',
        82 => 'Remboursement incertain',
        83 => 'Remboursement refusé',
        84 => 'Remboursement',
        85 => 'Rembours. traité par le marchand',
        9 => 'Paiement demandé',
        91 => 'Paiement en cours',
        92 => 'Paiement incertain',
        93 => 'Paiement refusé',
        94 => "Remb. Refusé par l'acquéreur",
        95 => 'Paiement traité par le marchand',
        96 => 'Refund reversed',
        99 => 'En cours de traitement',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }
}
