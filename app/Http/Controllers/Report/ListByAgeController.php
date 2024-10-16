<?php
declare(strict_types=1);

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\View\View as View;

/**
 *
 */
class ListByAgeController extends Controller
{
    /**
     * Restituisce l'index della lista soci per età
     *
     * @return View
     */
    public function index(): View
    {
        return view('report/age/index');
    }

    /**
     * Restituisce la lista dei soci organizzata in tabella
     *
     * @param $age
     * @return JsonResponse
     */
    public function data($age): JsonResponse
    {
        if (!is_numeric($age)) {
            return response()->json(
                ['error' => ['message' => 'L\'età non è un numero.']]
            );
        }

        $data = $this->customersByAge($age);

        $view = view('/report/age/data', ['data' => $data, 'age' => $age])->render();

        return response()->json(
            ['data' => [
                'view' => $view,
                'age' => $age,
                ]
            ]
        );
    }

    /**
     * Restituisce i soci sopra l'età passata come parametro
     *
     * @param $age
     * @return array
     */
    private function customersByAge($age): array
    {
        $year = date("Y") - $age;

        return DB::select(
            "select first_name, last_name,
                    strftime('%Y', birth_date) as birth_year,
                    municipality, phone, mobile_phone
                from customers
                where birth_year <= '{$year}'  and death_date = '' and revocation_date = ''
                order by last_name, first_name;",
        );
    }
}
