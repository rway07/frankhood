<?php

namespace App\Util;

/**
 *
 */
class DataValidator
{
    protected $returnMessage = "";

    /**
     * @param $year
     * @return bool
     */
    public function checkYear($year): bool
    {
        // Controllo che l'anno sia un numero
        if (!is_numeric($year)) {
            $this->returnMessage = "L'anno non è un numero.";
            return false;
        }

        // Controllo che l'anno sia in un range "accettabile"
        // La Confraternita esisterà ancora nel 2100??????
        if (($year < 0) || ($year > 2100)) {
            $this->returnMessage =  "Anno nel range sbagliato.";
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
