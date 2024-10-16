<?php
declare(strict_types=1);

namespace App\Util;

use Illuminate\Support\Facades\DB as DB;

/**
 *
 */
class OffersDataValidator {
    private $returnMessage = "";

    /**
     * Controlla che l'ID dell'offerta sia valido
     *
     * @param $idOffer
     * @return bool
     */
    public function checkOfferID($idOffer): bool
    {
        if (!is_numeric($idOffer)) {
            $this->returnMessage = "L'ID dell'offerta non è un numero.";
            return false;
        }

        $query = DB::select("select id
            from offers
            where id = {$idOffer};"
        );

        if (count($query) == 0) {
            $this->returnMessage = "L'ID dell'offerta non esiste.";
            return false;
        }

        return true;
    }

    /**
     * Restituisce il messaggio di errore
     *
     * @return string
     */
    public function getReturnMessage(): string
    {
        return $this->returnMessage;
    }
}
