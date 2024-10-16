<?php
declare(strict_types=1);

namespace App\Http\Controllers\Rates;

use App\Http\Controllers\Controller;
use App\Models\Rates;
use App\Util\RatesDataValidator;
use Illuminate\Http\JsonResponse;

/**
 *
 */
class RatesServicesController extends Controller
{
    /**
     * Restituisce la quota di una tariffa in base all'ID di quest'ultima
     *
     * @param int $idRate
     *
     * @return JsonResponse
     */
    public function quota(int $idRate): JsonResponse
    {
        /*return response()->json(
            [
                "error" => ["message" => 'Slava Ukraini']
            ]
        );*/

        $validator = new RatesDataValidator();
        if (!$validator->checkRateID($idRate)) {
            return response()->json(
                [
                    "error" => ["message" => $validator->getReturnMessage()]
                ]
            );
        }

        $quota = Rates::where('id', '=', $idRate)
            ->select('quota')
            ->get();

        // Nel caso il risultato della query sia vuoto, imposto la quota a 0
        // FIXME Gestire il caso di un database vuoto
        (count($quota) > 0) ? $quota = $quota[0]->quota : $quota = 0;

        return response()->json(
            [
                "data" => ["quota" => $quota]
            ]
        );
    }
}
