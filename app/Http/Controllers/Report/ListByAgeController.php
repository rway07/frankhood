<?php

namespace App\Http\Controllers\Report;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;

class ListByAgeController extends Controller
{
    /**
     * Restituisce l'index della lista soci per età
     *
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('report/age/index');
    }

    /**
     * Restituisce la lista dei soci organizzata in tabella
     *
     * @param $age
     * @return array
     */
    public function data($age)
    {
        $data = $this->customersByAge($age);

        return view('/report/age/data', ['data' => $data, 'age' => $age]);
    }

    /**
     * Restituisce i soci sopra l'età passata come parametro
     *
     * @param $age
     * @return array
     */
    private function customersByAge($age)
    {
        $year = strval(date("Y") - $age);

        $data = DB::select(
            'select first_name, last_name,
              strftime(\'%Y\', birth_date) as birth_year,
              municipality
            from customers
            where birth_year <= ?  and death_date = \'\' and revocation_date = \'\'
            order by last_name, first_name;',
            [
                $year
            ]
        );

        return $data;
    }
}
