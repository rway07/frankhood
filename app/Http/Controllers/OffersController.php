<?php

namespace App\Http\Controllers;
use DB;
use Exception;
use Redirect;
use App\Models\Offers;
use App\Http\Requests\OfferRequest;
use Illuminate\Http\Request;
use yajra\Datatables\Datatables;

class OffersController extends Controller
{
    /**
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $years = DB::select(
            "select distinct strftime('%Y', date) as year
            from offers
            order by year desc;"
        );

        return view(
            'offers.list',
            [
                'years' => $years
            ]
        );
    }

    /**
     * @return mixed
     */
    public function data($year)
    {
        $beginDate = $year . '-01-01';
        $endDate = $year . '-12-31';

        $offers = Offers::select(['id', 'date', 'description', 'amount'])
            ->orderBy('date', 'desc');

        if ($year != 0) {
            $offers = $offers->whereBetween('date', [$beginDate, $endDate]);
        }

        return Datatables::of($offers)
            ->editColumn('date', '{{ strftime("%d/%m/%Y", strtotime($date)) }}')
            ->editColumn('amount', '{{ $amount }} &euro;')
            ->addColumn('Modifica', function ($val) {
                return "<button type='button' class='btn btn-info btn-sm' onclick='edit(". $val->id .")'>
                                    <i class='fa fa-btn fa-edit'> </i> Modifica</button>";
            })
            ->addColumn('Elimina', function ($val) {
                return "<button id='ex_" . $val->id . "' type='button' class='btn btn-danger btn-sm'
                    onclick='destroy(". $val->id .")'>
                    <i class='fa fa-btn fa-trash'> </i> Elimina</button>";
            })
            ->rawColumns(['Modifica', 'Elimina'])
            ->make(true);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('offers.create');
    }

    /**
     * @param $idOffer
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($idOffer)
    {
        $offer = Offers::select(['id', 'description', 'date', 'amount'])
            ->where('id', $idOffer)
            ->get()
            ->first();

        return view('offers.create', ['offers' => $offer]);
    }

    /**
     * @param $data
     * @param $idOffer
     * @return bool
     */
    private function saveOffer($data, $idOffer)
    {
        $offer = null;

        if ($idOffer == 0) {
            $offer = new Offers();
        } else {
            $offer = Offers::find($idOffer);
        }

        $offer->description = $data['description'];
        $offer->date = $data['date'];
        $offer->amount = $data['amount'];
        $offer->save();

        return true;
    }

    /**
     * @param OfferRequest $request
     * @return mixed
     */
    public function store(OfferRequest $request)
    {
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();
            $this->saveOffer($validatedData, 0);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::to('offers/index')->withErrors('Transazione fallitta!');
        }

        return Redirect::to('offers/index');
    }

    /**
     * @param OfferRequest $request
     * @param $idOffer
     * @return mixed
     */
    public function update(OfferRequest $request, $idOffer)
    {
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();
            $this->saveOffer($validatedData, $idOffer);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::to('offers/index')->withErrors($e->getMessage());
        }

        return Redirect::to('offers/index');
    }

    /**
     * @param $idOffer
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($idOffer)
    {
        try {
            DB::beginTransaction();

            Offers::destroy($idOffer);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]
            );
        }

        return response()->json(['status' => 'OK', 'message' => 'Offerta eliminata']);
    }
}
