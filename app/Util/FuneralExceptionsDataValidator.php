<?php

namespace App\Util;

use Illuminate\Support\Facades\DB as DB;

/**
 *
 */
class FuneralExceptionsDataValidator {
    private $returnMessage = '';

    /**
     * @param $idException
     * @return bool
     */
    public function checkExceptionID($idException): bool
    {
        if (!is_numeric($idException)) {
            $this->returnMessage = "L'ID dell'eccezione deve essere un numero.";
            return false;
        }

        $query = DB::select(
            "select id
            from funerals_cost_exceptions
            where id = {$idException};"
        );

        if (count($query) == 0) {
            $this->returnMessage = "L'ID dell'eccezione non esiste.";
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
