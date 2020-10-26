<?php

namespace App\Http\Controllers\Report;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;

class EstimationController extends Controller
{
    /**
     * Restituisce la vista per il calcolo del preventivo dei nuovi soci
     *
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $years = DB::select(
            "select distinct year
            from rates
            order by year desc;"
        );

        return view('report/estimation/index', ['years' => $years]);
    }

    /**
     * Stampa la pagina del preventivo nuovo socio
     *
     * @param Request $request
     * @return mixed
     */
    public function printReport(Request $request)
    {
        $name = $request->first_name . ' ' . $request->last_name;
        $year = $request->years;
        $age = $year - date('Y', strtotime($request->birth_date));
        $rate = DB::select(
            "select quota
            from rates
            where year = ?;",
            [
                $year
            ]
        );

        $rate = $rate[0]->quota;
        $total = 0;

        if (($age >= 12) && ($age <= 20)) {
            $rate += 0;
        } elseif (($age > 20) && ($age <= 30)) {
            $rate += 10;
        } elseif (($age > 30) && ($age <=35)) {
            $rate += 20;
        } elseif (($age > 35) && ($age < 40)) {
            $rate += 30;
        } elseif (($age >= 40) && ($age <= 48)) {
            $total = 1750;
        } elseif (($age > 48) && ($age <= 59)) {
            $total = 2150;
        } elseif ($age > 59) {
            $total = 2650;
        }

        if ($total == 0) {
            $total = $rate * ($age - 12);
        }

        $pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadView(
            'report/estimation/print',
            [
                'name' => $name,
                'birth_date' => $request->birth_date,
                'total' => $total
            ]
        );

        return $pdf->inline();
    }
}
