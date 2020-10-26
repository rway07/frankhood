<?php

namespace App\Http\Controllers\Rates;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Exception;
use Redirect;

class FuneralExceptionsController extends Controller
{
    /**
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $exceptions = DB::select(
            'select f.id, c.first_name, c.last_name, r.year, f.cost
            from funerals_cost_exceptions f
            join customers c on f.customer_id = c.id
            join rates r on f.rate_id = r.id
            order by year desc;'
        );

        return view(
            'rates/funeral/index',
            [
                'exceptions' => $exceptions
            ]
        );
    }

    /**
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $years = DB::select(
            'select distinct strftime(\'%Y\', death_date) as year
            from customers
            where year is not null
            order by year desc'
        );

        $customers = DB::select(
            'select id, first_name, last_name
            from customers c
            where death_date between ? and ?;',
            [
                $years[0]->year . '-01-01',
                $years[0]->year . '-12-31'
            ]
        );

        return view(
            'rates/funeral/create',
            [
                'years' => $years,
                'customers' => $customers
            ]
        );
    }

    /**
     * @param $idException
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($idException)
    {
        $years = DB::select(
            'select distinct strftime(\'%Y\', death_date) as year
            from customers
            where year is not null
            order by year desc'
        );

        $customers = DB::select(
            'select id, first_name, last_name
            from customers c
            where death_date between ? and ?;',
            [
                $years[0]->year . '-01-01',
                $years[0]->year . '-12-31'
            ]
        );

        $exception = DB::select(
            'select f.id, f.customer_id, f.cost, r.year
            from funerals_cost_exceptions f
            join rates r on f.rate_id = r.id
            where f.id = ?;',
            [
                $idException
            ]
        );

        return view(
            'rates/funeral/create',
            [
                'years' => $years,
                'customers' => $customers,
                'exception' => $exception[0]
            ]
        );
    }

    /**
     * @param $idException
     * @param $data
     */
    private function storeException($idException, $data)
    {
        $idRate = DB::select(
            'select id
            from rates
            where year = ?;',
            [
                $data->year
            ]
        );

        if ($idException == 0) {
            DB::insert(
                'insert into funerals_cost_exceptions(rate_id, customer_id, cost)
                values (?, ?, ?);',
                [
                    $idRate[0]->id,
                    $data->dead_customers,
                    $data->quota
                ]
            );
        } else {
            DB::update(
                'update funerals_cost_exceptions
                set rate_id = ?, customer_id = ?, cost = ?
                where id = ?;',
                [
                    $idRate[0]->id,
                    $data->dead_customers,
                    $data->quota,
                    $idException
                ]
            );
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $this->validate(
                $request,
                [
                    'year' => 'required:date_format:Y',
                    'quota' => 'required:numeric',
                    'dead_customers' => 'required:integer'
                ]
            );

            DB::beginTransaction();
            $this->storeException(0, $request);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::to('rates/exceptions/create')
                ->withErrors('Transazione fallita: ' . $e->getMessage());
        }

        return Redirect::to('rates/exceptions/index');
    }

    /**
     * @param Request $request
     * @param $idException
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $idException)
    {
        try {
            $this->validate(
                $request,
                [
                    'year' => 'required:date_format:Y',
                    'quota' => 'required:numeric',
                    'dead_customers' => 'required:integer'
                ]
            );

            DB::beginTransaction();
            $this->storeException($idException, $request);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::to('rates/exceptions/create')->withErrors('Transazione fallita');
        }

        return Redirect::to('rates/exceptions/index');
    }

    /**
     * @param $idException
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($idException)
    {
        try {
            DB::beginTransaction();
            DB::delete(
                'delete from funerals_cost_exceptions
                    where id = ?;',
                [
                    $idException
                ]
            );

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    'status' => '500',
                    'message' => $e->getMessage()
                ]
            );
        }

        return response()->json(['status' => 'OK', 'message' => 'Eccezione eliminata']);
    }
}
