<?php

namespace App\Http\Controllers\Rates;

use App\Models\Rates;
use App\Http\Controllers\Controller;

class RatesServicesController extends Controller
{
    /**
     * Restituisce la quota di una tariffa in base all'ID di quest'ultima
     *
     * @param $idRate
     *
     * @return mixed
     */
    public function quota($idRate)
    {
        $quota = Rates::where('id', '=', $idRate)
            ->select('quota')
            ->get();

        return $quota;
    }
}
