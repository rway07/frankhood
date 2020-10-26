<?php

namespace App\Http\Controllers\Customers;

use App;
use App\Models\Customers;
use App\Models\Receipts;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use DB;
use Exception;
use Illuminate\Http\Request;
use Redirect;

/**
 * Class CustomersController
 * @package App\Http\Controllers
 */
class CustomersController extends Controller
{
    /**
     * Restituisce la vista con la lista dei soci
     *
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('customers/list');
    }

    /**
     * @return mixed
     */
    public function data()
    {
        $customers = Customers::select(
            [
                'id',
                DB::raw('(last_name || \' \' || first_name) as name'),
                'alias',
                'birth_date',
                'enrollment_year',
                'death_date',
                'revocation_date',
            ]
        )->orderBy('last_name', 'asc');

        return $customers->get()->toJson();
    }

    /**
     * Restituisce la vista per l'aggiunta di un nuovo socio
     *
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('customers/create');
    }

    /**
     * Salva un nuovo socio
     *
     * @param CustomerRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function store(CustomerRequest $request)
    {
        try {
            $validatedData = $request->validated();

            DB::beginTransaction();
            $this->saveCustomer($validatedData, 0);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::to('customers/create')
                ->withErrors('[customers.store] Transazione fallita! ' . $e->getMessage());
        }

        return redirect('customers/index')->with('status', 'saved');
    }

    /**
     * Restituisce la vista per la modifica di un socio
     *
     * @param $customerId
     *
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($customerId)
    {
        $customers = Customers::where('id', '=', $customerId)
            ->get()
            ->first();

        return view('customers/create', ['customers' => $customers]);
    }

    /**
     * Aggiorna un socio
     *
     * @param Request $request
     * @param $idCustomer
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function update(CustomerRequest $request, $idCustomer)
    {
        try {
            $validatedData = $request->validated();

            DB::beginTransaction();
            $this->saveCustomer($validatedData, $idCustomer);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::to('customers/create')
                ->withErrors('[customers.update] Transazione fallita! ' . $e->getMessage());
        }

        return redirect('customers/index')->with('status', 'updated');
    }

    /**
     * @param Request $request
     * @param $idCustomer
     * @return int
     */
    private function saveCustomer($data, $idCustomer)
    {
        $customer = null;

        if ($idCustomer == 0) {
            $customer = new Customers();
        } else {
            $customer = Customers::find($idCustomer);
        }

        $customer->first_name = ucwords(strtolower($data['first_name']));
        $customer->last_name = ucwords(strtolower($data['last_name']));
        $customer->alias = ucwords(strtolower($data['alias']));
        $customer->cf = $data['cf'];
        $customer->birth_date = $data['birth_date'];
        $customer->birth_place = $data['birth_place'];
        $customer->birth_province = $data['birth_province'];
        $customer->gender = $data['gender'];
        $customer->municipality = $data['municipality'];
        $customer->address = $data['address'];
        $customer->CAP = $data['CAP'];
        $customer->province = $data['province'];
        $customer->phone = $data['phone'];
        $customer->mobile_phone = $data['mobile_phone'];
        $customer->email = $data['email'];
        $customer->enrollment_year = $data['enrollment_year'];
        if ($data['death_date'] == null) {
            $customer->death_date = '';
        } else {
            $customer->death_date = $data['death_date'];
        }

        if ($data['revocation_date'] == null) {
            $customer->revocation_date = '';
        } else {
            $customer->revocation_date = $data['revocation_date'];
        }

        // FIXME Fix nel middleware
        if (array_key_exists('priorato', $data)) {
            $customer->priorato = $data['priorato'];
        } else {
            $customer->priorato = false;
        }

        $customer->save();

        return $customer->id;
    }

    /**
     * Elimina un socio
     *
     * @param $customerId
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function destroy($idCustomer)
    {
        // TODO Implementami!
        // Check if there are receipts for the current customer

        return view('customers/list');
    }


    /**
     * Restituisce la vista contenente ricevute e gruppo familiare di un socio nel corso degli anni.
     *
     * @param $idCustomer
     *
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function summary($idCustomer)
    {
        $data = [];
        $customer = Customers::where('id', '=', $idCustomer)
            ->select(DB::raw('(first_name || \' \' || last_name || \' (\' || alias || \')\') as name'))
            ->get()
            ->first();

        $receipts = DB::table('customers_receipts')
            ->where('customers_id', '=', $idCustomer)
            ->select(['year', 'number'])
            ->orderBy('year', 'desc')
            ->orderBy('number', 'asc')
            ->get();

        foreach ($receipts as $receipt) {
            $r = Receipts::where([
                ['year', '=', $receipt->year],
                ['number', '=', $receipt->number],
            ])->get();

            $c = DB::select(
                "select customers.id, (first_name || ' ' || last_name) as name,
                    alias
                from customers_receipts
                join customers on customers_id = customers.id
                where year = ? and number = ?",
                [
                    $receipt->year,
                    $receipt->number
                ]
            );

            $data[] = [
                'date' => $r[0]->date,
                'year' => $r[0]->year,
                'number' => $r[0]->number,
                'total' => $r[0]->total,
                'head' => $r[0]->customers_id,
                'customers' => $c
            ];
        }

        return view('customers/util/summary', ['customer' => $customer, 'data' => $data]);
    }
}
