<?php

namespace App\Http\Controllers\Rates;

use App\Http\Controllers\Controller;
use App\Http\Requests\RateRequest;
use App\Models\Rates;
use App\Models\Receipts;
use App\Util\RatesDataValidator;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Support\Facades\Redirect as Redirect;
use Illuminate\View\View as View;

/**
 * Class RatesController
 * @package App\Http\Controllers
 */
class RatesController extends Controller
{
    /**
     * Restituisce la vista con la liste dele tariffe
     *
     * @return View
     */
    public function index(): View
    {
        $rates = Rates::select(['id', 'year', 'funeral_cost', 'quota'])
            ->orderBy('year', 'desc')
            ->get();

        return view('rates/index', ['rates' => $rates]);
    }

    /**
     * Restituisce la vista per l'aggiunta di una tariffa
     *
     * @return View
     */
    public function create(): View
    {
        return view('rates/create');
    }

    /**
     *  Restiuisce la vista per la modifica di una tariffa
     *
     * @param $idRate
     * @return RedirectResponse | View
     */
    public function edit($idRate)
    {
        $validator = new RatesDataValidator();

        if (!$validator->checkRateID($idRate)) {
            return Redirect::to('rates/index')
                ->withErrors($validator->getReturnMessage());
        }

        $currentRate = Rates::select(['id', 'year', 'funeral_cost', 'quota'])
            ->where('id', '=', $idRate)
            ->get();

        return view(
            'rates/create',
            [
                'rate' => $currentRate->first()
            ]
        );
    }

    /**
     * Salva un tariffa
     *
     * @param RateRequest $request
     * @return RedirectResponse
     */
    public function store(RateRequest $request): RedirectResponse
    {
        try {
            $validatedData = $request->validated();

            $validator = new RatesDataValidator();
            if (!$validator->rateYearAvailable($validatedData['year'])) {
                return Redirect::to('rates/create')->withErrors($validator->getReturnMessage());
            }

            DB::beginTransaction();
            $rate = new Rates();
            $rate->year = $validatedData['year'];
            $rate->quota = $validatedData['quota'];
            $rate->funeral_cost = $validatedData['funeral_cost'];
            $rate->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::to('rates/create')->withErrors($e->getMessage());
        }

        return Redirect::to("rates/index")->with('status', 'Tariffa aggiunta.');
    }

    /**
     * Aggiorna una tariffa
     *
     * @param RateRequest $request
     * @param $idRate
     * @return RedirectResponse
     */
    public function update(RateRequest $request, $idRate): RedirectResponse
    {
        try {
            $validator = new RatesDataValidator();
            if (!$validator->checkRateID($idRate)) {
                return Redirect::to('rates/index')->withErrors($validator->getReturnMessage());
            }

            $validatedData = $request->validated();

            DB::beginTransaction();
            $rate = Rates::find($idRate);
            $rate->year = $validatedData['year'];
            $rate->funeral_cost = $validatedData['funeral_cost'];
            // Salvo la tariffa corrente per il l'aggiornamento delle ricevute
            $currentQuota = $rate->quota;
            $rate->quota = $validatedData['quota'];
            $rate->save();

            // Aggiorna le ricevute con la nuova tariffa
            $receipts = Receipts::select(['total'])
                ->where('rates_id', '=', $rate->id)
                ->get();

            foreach ($receipts as $receipt) {
                // Ottengo il numero di persone nella ricevuta corrente usando la vecchia tariffa
                $num = $receipt->total / $currentQuota;
                // Calcolo il nuovo totale
                $receipt->total = $num * $rate->quota;
                $receipt->save();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::to('rates/create')->withErrors($e->getMessage());
        }

        return Redirect::to('rates/index')->with('status', 'Tariffa aggiornata.');
    }

    /**
     * Elimina un tariffa
     *
     * @return false
     */
    public function destroy()
    {
        // Amazing useless placeholder
        return false;
    }
}
