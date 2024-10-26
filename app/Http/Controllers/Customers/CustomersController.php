<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Models\Customers;
use App\Models\Receipts;
use App\Util\CustomersDataValidator;
use Exception as Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Support\Facades\Redirect as Redirect;
use Illuminate\View\View as View;


/**
 * Class CustomersController
 * @package App\Http\Controllers
 */
class CustomersController extends Controller
{
    /**
     * Restituisce la vista con la lista dei soci
     *
     */
    public function index()
    {
        return view('customers/list');
    }

    /**
     * Restituisce i dati per il Datatable dei soci
     *
     * @return string
     */
    public function data(): string
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
     * @return View
     */
    public function create(): View
    {
        return view('customers/create');
    }

    /**
     * Salva un nuovo socio
     *
     * @param CustomerRequest $request
     * @return RedirectResponse
     */
    public function store(CustomerRequest $request): RedirectResponse
    {
        try {
            // Validazione input
            $validatedData = $request->validated();

            DB::beginTransaction();
            $this->saveCustomer($validatedData, 0);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::to('customers/create')->withErrors($e->getMessage());
        }

        return redirect('customers/index')->with('status', 'Socio aggiunto.');
    }

    /**
     * Restituisce la vista per la modifica di un socio
     *
     * @param $idCustomer
     * @return RedirectResponse | View
     */
    public function edit($idCustomer)
    {
        // Validazione ID Socio
        $validator = new CustomersDataValidator();

        if (!$validator->checkCustomerID($idCustomer)) {
            return Redirect::to('customers/index')->withErrors($validator->getReturnMessage());
        }

        // Ottengo i dati del socio
        $customers = Customers::where('id', '=', $idCustomer)
            ->get()
            ->first();

        return view('customers/create', ['customers' => $customers]);
    }

    /**
     * Aggiorna un socio
     *
     * @param CustomerRequest $request
     * @param $idCustomer
     * @return RedirectResponse
     */
    public function update(CustomerRequest $request, $idCustomer): RedirectResponse
    {
        try {
            // Validazione dei dati
            $validatedData = $request->validated();
            $dataValidator = new CustomersDataValidator();
            if (!$dataValidator->checkCustomerID($idCustomer)) {
                throw new Exception($dataValidator->getReturnMessage());
            }

            DB::beginTransaction();
            $this->saveCustomer($validatedData, $idCustomer);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::to('customers/create')->withErrors($e->getMessage());
        }

        return redirect('customers/index')->with('status', 'Socio aggiornato.');
    }

    /**
     * Salva i dati del nuovo socio o aggiorna uno corrente
     *
     * @param $data
     * @param $idCustomer
     */
    private function saveCustomer($data, $idCustomer)
    {
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
        $customer->CAP = $data['cap'];
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
    }

    /**
     * Elimina un socio
     *
     * @return false
     */
    public function destroy(): bool
    {
        // TODO Implementami!
        // Check if there are receipts for the current customer

        return false;
    }

    /**
     * Restituisce la vista contenente ricevute e gruppo familiare di un socio nel corso degli anni.
     *
     * @param $idCustomer
     * @return RedirectResponse | View
     */
    public function summary($idCustomer)
    {
        // Validazione ID Socio
        $validator = new CustomersDataValidator();
        if (!$validator->checkCustomerID($idCustomer)) {
            return Redirect::to('customers/index')->withErrors($validator->getReturnMessage());
        }

        $data = [];
        // Ottego i dati del socio
        $customer = Customers::where('id', '=', $idCustomer)
            ->select(DB::raw('(first_name || \' \' || last_name || \' (\' || alias || \')\') as name'))
            ->get()
            ->first();

        // Ottengo tutte le ricevute del socio
        $receipts = DB::table('customers_receipts')
            ->where('customers_id', '=', $idCustomer)
            ->select(['year', 'number'])
            ->orderBy('year', 'desc')
            ->orderBy('number', 'asc')
            ->get();

        // Per ogni ricevuta, carico i dati del gruppo familiare
        foreach ($receipts as $receipt) {
            $currentReceipt = Receipts::where([
                ['year', '=', $receipt->year],
                ['number', '=', $receipt->number],
            ])->get();

            $currentCustomer = DB::select(
                "select customers.id, (first_name || ' ' || last_name) as name,
                    alias
                from customers_receipts
                join customers on customers_id = customers.id
                where year = {$receipt->year} and number = {$receipt->number}"
            );

            $data[] = [
                'date' => $currentReceipt[0]->date,
                'year' => $currentReceipt[0]->year,
                'number' => $currentReceipt[0]->number,
                'total' => $currentReceipt[0]->total,
                'head' => $currentReceipt[0]->customers_id,
                'customers' => $currentCustomer
            ];
        }

        return view('customers/util/summary', ['customer' => $customer, 'data' => $data]);
    }
}
