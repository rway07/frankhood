<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Models\Expenses;
use App\Util\DataValidator;
use App\Util\ExpensesDataValidator;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App as App;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Support\Facades\Redirect as Redirect;
use Illuminate\View\View;
use yajra\Datatables\DataTables;

/**
 *
 */
class ExpensesController extends Controller
{
    /**
     * Restituisce la vista con la lista delle spese
     *
     * @return View
     */
    public function index(): View
    {
        $years = DB::select(
            'select distinct strftime(\'%Y\', date) as year
            from expenses
            order by year desc;'
        );

        return view(
            'expenses/list',
            [
                'years' => $years
            ]
        );
    }

    /**
     * Restituisce i dati per il DataTable nella vista principale
     *
     * @param $year
     * @return mixed
     * @throws Exception
     */
    public function data($year)
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

        $expenses = Expenses::select(['id', 'date', 'description', 'amount'])
            ->orderBy('date', 'desc');

        if ($year != 0) {
            $expenses = $expenses->whereBetween('date', [$beginDate, $endDate]);
        }

        return DataTables::of($expenses)
            ->editColumn('date', '{{ strftime(\'%d/%m/%Y\', strtotime($date)) }}')
            ->editColumn('amount', '{{ $amount }} €')
            ->addColumn('Stampa', function ($entry) {
                return view('common.print', ['subject' => 'expenses', 'idSubject' => $entry->id]);
            })
            ->addColumn('Modifica', function ($entry) {
                return view('common.edit', ['subject' => 'expenses', 'idSubject' => $entry->id]);
            })
            ->addColumn('Elimina', function ($entry) {
                return view(
                    'common.delete',
                    [
                        'subject' => 'expenses',
                        'idSubject' => $entry->id
                    ]
                );
            })
            ->rawColumns(['Stampa', 'Modifica', 'Elimina'])
            ->make(true);
    }

    /**
     * Restituisce la vista per l'aggiunta di una nuova spesa
     *
     * @return View
     */
    public function create(): View
    {
        return view('expenses.create');
    }

    /**
     * Restituisce la vista per la modifica di una spesa esistente
     *
     * @param $idExpense
     * @return View | RedirectResponse
     */
    public function edit($idExpense)
    {
        $validator = new ExpensesDataValidator();
        if (!$validator->checkExpenseID($idExpense)) {
            return Redirect::to('expenses/index')->withErrors($validator->getReturnErrorMessage());
        }

        $expense = Expenses::select(['id', 'description', 'date', 'amount'])
            ->where('id', $idExpense)
            ->get()
            ->first();

        return view('expenses.create', ['expense' => $expense]);
    }

    /**
     * @param $data
     * @param $idExpense
     * @return void
     */
    private function saveExpense($data, $idExpense)
    {
        if ($idExpense == 0) {
            $expense = new Expenses();
        } else {
            $expense = Expenses::find($idExpense);
        }

        $expense->description = $data['description'];
        $expense->date = $data['date'];
        $expense->amount = $data['amount'];
        $expense->save();
    }

    /**
     * Salva una nuova spesa
     *
     * @param ExpenseRequest $request
     * @return RedirectResponse
     */
    public function store(ExpenseRequest $request): RedirectResponse
    {
        try {
            $validatedData = $request->validated();

            DB::beginTransaction();
            $this->saveExpense($validatedData, 0);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::to('expenses/index')->withErrors($e->getMessage());
        }

        return Redirect::to('expenses/index')->with('status', 'Spesa aggiunta.');
    }

    /**
     * Aggiorna una nuova spesa
     *
     * @param ExpenseRequest $request
     * @param $idExpense
     * @return RedirectResponse
     */
    public function update(ExpenseRequest $request, $idExpense): RedirectResponse
    {
        try {
            $validator = new ExpensesDataValidator();
            if (!$validator->checkExpenseID($idExpense)) {
                return Redirect::to('expenses/index')->withErrors($validator->getReturnErrorMessage());
            }

            $validatedData = $request->validated();

            DB::beginTransaction();
            $this->saveExpense($validatedData, $idExpense);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::to('expenses/index')->withErrors($e->getMessage());
        }

        return Redirect::to('expenses/index')->with('status', 'Spesa aggiornata.');
    }

    /**
     * Elimina una spesa
     *
     * @param $idExpense
     * @return JsonResponse
     */
    public function destroy($idExpense): JsonResponse
    {
        try {
            $validator = new ExpensesDataValidator();
            if (!$validator->checkExpenseID($idExpense)) {
                return response()->json(
                    ['error' => ['message' => $validator->getReturnErrorMessage()]]
                );
            }

            DB::beginTransaction();
            Expenses::destroy($idExpense);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(
                ['error' => ['message' => $e->getMessage()]]
            );
        }

        return response()->json(
            ['data' => ['message' => 'Spesa eliminata.']]
        );
    }

    /**
     * Stampa una spesa
     *
     * @param $idExpense
     * @return mixed
     */
    public function printReceipt($idExpense)
    {
        $validator = new ExpensesDataValidator();
        if (!$validator->checkExpenseID($idExpense)) {
            return Redirect::to('expenses/index')->withErrors($validator->getReturnErrorMessage());
        }

        $data = DB::select(
            "select id, description, date, amount
            from expenses
            where id = {$idExpense};"
        );

        $pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadView(
            'expenses/print',
            [
                'expense' => $data[0]
            ]
        );

        return $pdf->inline();
    }
}
