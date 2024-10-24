<?php

namespace App\Util;

use Illuminate\Support\Facades\DB as DB;

/**
 *
 */
class RatesDataValidator
{
    protected $returnMessage = "";

    /**
     * @param $idRate
     * @return bool
     */
    public function checkRateID($idRate): bool
    {
        if (!is_numeric($idRate)) {
            $this->returnMessage = "ID tariffa non è un numero.";
            return false;
        }

        $query = DB::select(
            "SELECT *
            FROM rates
            WHERE id = '$idRate'"
        );

        if (count($query) == 0) {
            $this->returnMessage = "ID tariffa non trovato.";
            return false;
        }

        return true;
    }

    /**
     * @param $year
     * @return bool
     */
    public function rateYearAvailable($year): bool
    {
        $yearValidator = new DataValidator();
        if (!$yearValidator->checkYear($year)) {
            $this->returnMessage = $yearValidator->getReturnMessage();
            return false;
        }

        $query = DB::select(
            "select year
            from rates
            where year = {$year}"
        );

        if (count($query) > 0) {
            $this->returnMessage = "La tariffa per l'anno selezionato esiste già.";
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getReturnMessage(): string
    {
        return $this->returnMessage;
    }
}
