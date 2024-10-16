<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\OfferRequest;
use App\Models\Offers;
use App\Util\DataValidator;
use App\Util\OffersDataValidator;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App as App;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Support\Facades\Redirect as Redirect;
use Illuminate\View\View as View;
use yajra\Datatables\DataTables;

/**
 *
 */
class OffersController extends Controller
{
    /**
     * Restituisce la vista con la lista delle offerte
     *
     * @return View
     */
    public function index(): View
    {
        $years = DB::select(
            'select distinct strftime(\'%Y\', date) as year
            from offers
            order by year desc;'
        );

        return view(
            'offers.list',
            [
                'years' => $years
            ]
        );
    }


    /**
     * Restituisce i dati per il DataTable nella vista principale
     *
     * @param $year
     * @return JsonResponse
     * @throws Exception
     */
    public function data($year): JsonResponse
    {
        $validator = new DataValidator();
        if (!$validator->checkYear($year)) {
            return response()->json(
                [
                    'draw' => 0,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => $validator->getReturnMessage()
                ]
            );
        }

        $beginDate = $year . '-01-01';
        $endDate = $year . '-12-31';

        $offers = Offers::select(['id', 'date', 'description', 'amount'])
            ->orderBy('date', 'desc');

        if ($year != 0) {
            $offers = $offers->whereBetween('date', [$beginDate, $endDate]);
        }

        return DataTables::of($offers)
            ->editColumn('date', '{{ strftime(\'%d/%m/%Y\', strtotime($date)) }}')
            ->editColumn('amount', '{{ $amount }} €')
            ->addColumn('Stampa', function ($entry) {
                return view('common.print', ['subject' => 'offers', 'idSubject' => $entry->id]);
            })
            ->addColumn('Modifica', function ($entry) {
                return view('common.edit', ['subject' => 'offers', 'idSubject' => $entry->id]);
            })
            ->addColumn('Elimina', function ($entry) {
                return view(
                    'common.delete',
                    [
                        'subject' => 'offers',
                        'idSubject' => $entry->id
                    ]
                );
            })
            ->rawColumns(['Stampa', 'Modifica', 'Elimina'])
            ->make(true);
    }

    /**
     * Restituisce la vista per l'aggiunta di una nuova offerta
     *
     * @return View
     */
    public function create(): View
    {
        return view('offers.create');
    }

    /**
     * Restituisce la vista per la modifica di un'offerta esistente
     *
     * @param $idOffer
     * @return View | RedirectResponse
     */
    public function edit($idOffer)
    {
        $validator = new OffersDataValidator();
        if (!$validator->checkOfferID($idOffer)) {
            return Redirect::to('offers/index')->withErrors($validator->getReturnMessage());
        }

        $offer = Offers::select(['id', 'description', 'date', 'amount'])
            ->where('id', $idOffer)
            ->get()
            ->first();

        return view('offers.create', ['offer' => $offer]);
    }

    /**
     * @param $data
     * @param $idOffer
     * @return void
     */
    private function saveOffer($data, $idOffer)
    {
        if ($idOffer == 0) {
            $offer = new Offers();
        } else {
            $offer = Offers::find($idOffer);
        }

        $offer->description = $data['description'];
        $offer->date = $data['date'];
        $offer->amount = $data['amount'];
        $offer->save();
    }

    /**
     * Salva una nuova offerta
     *
     * @param OfferRequest $request
     * @return RedirectResponse
     */
    public function store(OfferRequest $request): RedirectResponse
    {
        try {
            $validatedData = $request->validated();

            DB::beginTransaction();
            $this->saveOffer($validatedData, 0);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::to('offers/index')->withErrors($e->getMessage());
        }

        return Redirect::to('offers/index')->with('status', 'Offerta aggiunta.');
    }

    /**
     * Aggiorna una nuova offerta
     *
     * @param OfferRequest $request
     * @param $idOffer
     * @return RedirectResponse
     */
    public function update(OfferRequest $request, $idOffer): RedirectResponse
    {
        try {
            $validator = new OffersDataValidator();
            if (!$validator->checkOfferID($idOffer)) {
                return Redirect::to('offers/index')->withErrors($validator->getReturnMessage());
            }

            $validatedData = $request->validated();

            DB::beginTransaction();
            $this->saveOffer($validatedData, $idOffer);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::to('offers/index')->withErrors($e->getMessage());
        }

        return Redirect::to('offers/index')->with('status', 'Offerta aggiornata');
    }

    /**
     * Elimina un'offerta
     *
     * @param $idOffer
     * @return JsonResponse
     */
    public function destroy($idOffer): JsonResponse
    {
        try {
            $validator = new OffersDataValidator();
            if (!$validator->checkOfferID($idOffer)) {
                return response()->json(
                    ['error' => ['message' => $validator->getReturnMessage()]]
                );
            }

            DB::beginTransaction();
            Offers::destroy($idOffer);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(
                ['error' => ['message' => $e->getMessage()]]
            );
        }

        return response()->json(
            ['data' => ['message' => 'Offerta eliminata.']]
        );
    }

    /**
     * Stampa un'offerta
     *
     * @param $idOffer
     * @return mixed
     */
    public function printReceipt($idOffer)
    {
        $validator = new OffersDataValidator();
        if (!$validator->checkOfferID($idOffer)) {
            return Redirect::to('offers/index')->withErrors($validator->getReturnMessage());
        }

        $data = DB::select(
            'select id, description, date, amount
            from offers
            where id = ?;',
            [
                $idOffer
            ]
        );

        $pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadView(
            'offers/print',
            [
                'offer' => $data[0]
            ]
        );

        return $pdf->inline();
    }
}
