<?php
declare(strict_types=1);

namespace App\Util;

use Illuminate\Support\Facades\DB as DB;

/**
 *
 */
class CustomersDataValidator extends DataValidator
{
    /**
     * @param $idCustomer
     * @return bool
     */
    public function checkCustomerID($idCustomer): bool
    {
        if (!is_numeric($idCustomer)) {
            $this->returnMessage = "L'ID del socio non è un numero.";
            return false;
        }

        $query = DB::select(
            'select id
            from customers
            where id = ?',
            [
                $idCustomer
            ]
        );

        if (count($query) == 0) {
            $this->returnMessage = "L'ID del socio non esiste.";
            return false;
        }

        return true;
    }
}
