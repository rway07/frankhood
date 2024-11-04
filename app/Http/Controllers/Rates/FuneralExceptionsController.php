<?php

namespace App\Http\Controllers\Rates;

use App\Http\Controllers\Controller;
use App\Http\Requests\RateExceptionRequest;
use App\Util\CustomersDataValidator;
use App\Util\FuneralExceptionsDataValidator;
use Exception;
use Illuminate\Http\JsonResponse as JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Support\Facades\Redirect as Redirect;
use Illuminate\View\View as View;

/**
 *
 */
class FuneralExceptionsController extends Controller
{
    /**
     * @return array
     */
    private function getYears(): array
    {
        return DB::select(
            'select distinct strftime(\'%Y\', death_date) as year
            from customers
            where year is not null
            order by year desc'
        );
    }

    /**
     * @param $year
     * @return array
     */
    private function getCustomers($year): array
    {
        return DB::select(
            'select id, first_name, last_name
            from customers c
            where death_date between ? and ?;',
            [
                $year . '-01-01',
                $year . '-12-31'
            ]
        );
    }

    /**
     * Restituisce la vista principale delle eccezioni dei funerali
     *
     * @return View
     */
    public function index(): View
    {
        $exceptions = DB::select(
            'select f.id, c.first_name, c.last_name, r.year, f.cost
            from funerals_cost_exceptions f
            join customers c on f.customer_id = c.id
            join rates r on f.rate_id = r.id
            order by year desc;'
        );

        return view(
            'rates/exceptions/index',
            [
                'exceptions' => $exceptions
            ]
        );
    }

    /**
     * Restituisce la vista per creare un nuova eccezione per un funerale
     *
     * @return View
     */
    public function create(): View
    {
        // Ottengo tutti gli anni dove ci sono stati dei morti
        $years = $this->getYears();
        // Carico i soci deceduti solo dell'anno più recente, gli altri verranno caricati dinamicamente
        $customers = $this->getCustomers($years[0]->year);

        return view(
            'rates/exceptions/create',
            [
                'years' => $years,
                'customers' => $customers
            ]
        );
    }

    /**
     * @param $idException
     * @return RedirectResponse | View
     */
    public function edit($idException)
    {
        $validator = new FuneralExceptionsDataValidator();

        if (!$validator->checkExceptionID($idException)) {
            return Redirect::to('rates/exceptions/index')->withErrors($validator->getReturnMessage());
        }

        $years = $this->getYears();
        $customers = $this->getCustomers($years[0]->year);

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
            'rates/exceptions/create',
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
        $validator  = new FuneralExceptionsDataValidator();
        if ($validator->rateYearAvailable($data['year'])) {
            return Redirect::to('rates/exception/create')
                ->withErrors('Errore con l\'anno selezionato');
        }

        $customerValidator = new CustomersDataValidator();
        if (!$customerValidator->checkCustomerID($data['dead_customers'])) {
            return Redirect::to('rates/exception/create')
                ->withErrors($customerValidator->getReturnMessage());
        }

        $idRate = DB::select(
            'select id
            from rates
            where year = ?;',
            [
                $data['year']
            ]
        );
        $idRate = $idRate[0]->id;

        if ($idException == 0) {
            DB::insert(
                'insert into funerals_cost_exceptions(rate_id, customer_id, cost)
                values (?, ?, ?);',
                [
                    $idRate,
                    $data['dead_customers'],
                    $data['quota']
                ]
            );
        } else {
            DB::update(
                'update funerals_cost_exceptions
                set rate_id = ?, customer_id = ?, cost = ?
                where id = ?;',
                [
                    $idRate,
                    $data['dead_customers'],
                    $data['quota'],
                    $idException
                ]
            );
        }
    }

    /**
     * @param RateExceptionRequest $request
     * @return RedirectResponse
     */
    public function store(RateExceptionRequest $request): RedirectResponse
    {
        try {
            $validatedData = $request->validated();

            DB::beginTransaction();
            $this->storeException(0, $validatedData);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::to('rates/exceptions/create')->withErrors($e->getMessage());
        }

        return Redirect::to('rates/exceptions/index')->with('status', 'Eccezione aggiunta.');
    }

    /**
     * @param RateExceptionRequest $request
     * @param $idException
     * @return RedirectResponse
     */
    public function update(RateExceptionRequest $request, $idException): RedirectResponse
    {
        try {
            $validator = new FuneralExceptionsDataValidator();
            if (!$validator->checkExceptionID($idException)) {
                return Redirect::to('rates/exception/create')->withErrors($validator->getReturnMessage());
            }

            $validatedData = $request->validated();

            DB::beginTransaction();
            $this->storeException($idException, $validatedData);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::to('rates/exceptions/create')->withErrors($e->getMessage());
        }

        return Redirect::to('rates/exceptions/index')->with('status', 'Eccezione aggiornata.');
    }

    /**
     * @param $idException
     * @return JsonResponse
     */
    public function destroy($idException): JsonResponse
    {
        try {
            DB::beginTransaction();
            // FIXME Does it throw an exception in case of error??
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
                ['error' => ['message' => $e->getMessage()]]
            );
        }

        return response()->json(
            ['data' => ['message' => 'Eccezione funerale eliminata.']]
        );
    }
}
