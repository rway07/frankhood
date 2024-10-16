<?php

declare(strict_types=1);

namespace App\Util;

use Illuminate\Support\Facades\DB as DB;

/**
 *
 */
class ReceiptDataValidator extends DataValidator
{
    /**
     * @param $year
     * @param $number
     * @return bool
     */
    public function checkReceiptNumber($year, $number): bool
    {
        if (!$this->checkYear($year)) {
            $this->returnMessage = $this->getReturnMessage();
            return false;
        }

        if (!is_numeric($number)) {
            $this->returnMessage = "Il numero di ricevuta non è un numero.";
            return false;
        }

        $data = DB::select(
            "select year, number
            from receipts
            where year = {$year} and number = {$number}"
        );

        if (count($data) == 0) {
            $this->returnMessage = "La ricevuta non esiste";
            return false;
        }

        return true;
    }

    /**
     * @param $year
     * @param $customers
     * @return bool
     */
    public function checkCustomersInExistingReceipt($year, $customers): bool
    {
        foreach ($customers as $customer) {
            $result = DB::select(
                "select number
                from customers_receipts
                where year = {$year} and customers_id = {$customer}"
            );

            if (count($result) > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $paymentMethod
     * @return bool
     */
    public function checkPaymentMethod($paymentMethod): bool
    {
        if (!is_numeric($paymentMethod)) {
            $this->returnMessage = "Metodo di pagamento non valido.";
            return false;
        }

        if ($paymentMethod == 0) {
            return true;
        }

        $data = DB::select(
            "select *
            from payment_types
            where id = {$paymentMethod}"
        );

        if (count($data) == 0) {
            $this->returnMessage = "Il metodo di pagamento non esiste.";
            return false;
        }

        return true;
    }
}
