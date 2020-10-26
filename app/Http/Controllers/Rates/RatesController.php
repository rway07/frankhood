<?php

namespace App\Http\Controllers\Rates;

use App\Models\Rates;
use App\Models\Receipts;
use App\Http\Requests\RateRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Exception;
use Redirect;

/**
 * Class RatesController
 * @package App\Http\Controllers
 */
class RatesController extends Controller
{
    /**
     * Restituisce la vista con la liste dele tariffe
     *
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $rates = Rates::select(['id', 'year', 'funeral_cost', 'quota'])
            ->get();

        return view('rates/index', ['rates' => $rates]);
    }

    /**
     * Restituisce la vista per l'aggiunta di una tariffa
     *
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('rates/create');
    }

    /**
     * Restiuisce la vista per la modifica di una tariffa
     *
     * @param $idRate
     *
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($idRate)
    {
        $current = Rates::select(['id', 'year', 'funeral_cost', 'quota'])
            ->where('id', '=', $idRate)
            ->get();

        return view(
            'rates/create',
            [
                'current' => $current->first()
            ]
        );
    }

    /**
     * Salva un tariffa
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function store(RateRequest $request)
    {
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();
            $rate = new Rates();
            $rate->year = $validatedData['year'];
            $rate->quota = $validatedData['quota'];
            $rate->funeral_cost = $validatedData['funeral_cost'];
            $rate->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::to('rates/create')->withErrors('Transazione fallita!');
        }

        return Redirect::to("rates/index");
    }

    /**
     * Aggiorna un tariffa
     *
     * @param Request $request
     * @param $idRate
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function update(RateRequest $request, $idRate)
    {
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();
            $rate = Rates::find($idRate);
            $rate->year = $validatedData['year'];
            $rate->funeral_cost = $validatedData['funeral_cost'];
            $currentQuota = $rate->quota;
            $rate->quota = $validatedData['quota'];
            $rate->save();

            //update receipts with the new rate
            $receipts = Receipts::select(['total'])
                ->where('rates_id', '=', $rate->id)
                ->get();

            foreach ($receipts as $receipt) {
                $num = $receipt->total / $currentQuota;
                $receipt->total = $num * $rate->quota;
                $receipt->save();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::to('rates/create')->withErrors('Transazione fallita!');
        }

        return Redirect::to('rates/index');
    }

    /**
     * Elimina una tariffa
     *
     * @param Rates $rate
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function destroy($idRate)
    {
        /*
         * TODO Implementazione futura se necessaria
        try {
            $check = DB::select(
                "select distinct r.id
                from rates r
                join receipts r2 on r.id = r2.rates_id
                where r.id = ?;",
                [
                    $idRate
                ]
            );

            if ($check[0]->id != null) {

            } else {

            }
        } catch (Exception $e) {

        }

        return Redirect::to('rates');
        */
    }
}
