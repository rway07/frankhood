<?php
declare(strict_types=1);

namespace App\Util;

use Illuminate\Support\Facades\DB as DB;

/**
 *
 */
class ExpensesDataValidator {
    private $returnMessage = "";

    /**
     * Controlla che l'ID della spesa sia valido
     *
     * @param $idExpense
     * @return bool
     */
    public function checkExpenseID($idExpense): bool
    {
        if (!is_numeric($idExpense)) {
            $this->returnMessage = "L'ID della spesa non è un numero.";
            return false;
        }

        $query = DB::select("select id
            from expenses
            where id = {$idExpense};"
        );

        if (count($query) == 0) {
            $this->returnMessage = "L'ID della spesa non esiste.";
            return false;
        }

        return true;
    }

    /**
     * Restituisce il messaggio di errore
     *
     * @return string
     */
    public function getReturnErrorMessage(): string {
        return $this->returnMessage;
    }
}
