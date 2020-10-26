<?php

namespace App\Http\Controllers;

use DB;
use Exception;
use Redirect;
use App\Models\Expenses;
use App\Http\Requests\ExpenseRequest;
use Illuminate\Http\Request;
use yajra\Datatables\Datatables;

class ExpensesController extends Controller
{
    /**
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $years = DB::select(
            "select distinct strftime('%Y', date) as year
            from expenses
            order by year desc;"
        );

        return view(
            'expenses/list',
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

        $expenses = Expenses::select(['id', 'date', 'description', 'amount'])
            ->orderBy('date', 'desc');

        if ($year != 0) {
            $expenses = $expenses->whereBetween('date', [$beginDate, $endDate]);
        }

        return Datatables::of($expenses)
            ->editColumn('date', '{{ strftime("%d/%m/%Y", strtotime($date)) }}')
            ->editColumn('amount', '{{ $amount }} €')
            ->addColumn('Modifica', function ($val) {
                return "<button type='button' class='btn btn-info btn-sm' onclick='edit(" . $val->id . ")'>
                                    <i class='fa fa-btn fa-edit'> </i> Modifica</button>";
            })
            ->addColumn('Elimina', function ($val) {
                return "<button id='ex_" . $val->id . "' type='button' class='btn btn-danger btn-sm'
                    onclick='destroy(" . $val->id . ")'>
                    <i class='fa fa-btn fa-trash'> </i> Elimina</button>";
            })
            ->rawColumns(['Modifica', 'Elimina'])
            ->make(true);
    }

    public function create()
    {
        return view('expenses.create');
    }

    /**
     * @param $idExpense
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($idExpense)
    {
        $expense = Expenses::select(['id', 'description', 'date', 'amount'])
            ->where('id', $idExpense)
            ->get()
            ->first();

        return view('expenses.create', ['expense' => $expense]);
    }

    /**
     * TODO Error handling
     *
     * @param Request $request
     * @param $idExpense
     * @return bool
     */
    private function saveExpense($data, $idExpense)
    {
        $expense = null;

        if ($idExpense == 0) {
            $expense = new Expenses();
        } else {
            $expense = Expenses::find($idExpense);
        }

        $expense->description = $data['description'];
        $expense->date = $data['date'];
        $expense->amount = $data['amount'];
        $expense->save();

        return true;
    }

    public function store(ExpenseRequest $request)
    {
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();
            $this->saveExpense($validatedData, 0);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::to('expenses/index')->withErrors('Transazione fallitta!');
        }

        return Redirect::to('expenses/index');
    }

    public function update(ExpenseRequest $request, $idExpense)
    {
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();
            $this->saveExpense($validatedData, $idExpense);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::to('expenses/index')->withErrors($e->getMessage());
        }

        return Redirect::to('expenses/index');
    }

    public function destroy($idExpense)
    {
        try {
            DB::beginTransaction();

            Expenses::destroy($idExpense);

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

        return response()->json(['status' => 'OK', 'message' => 'Spesa eliminata']);
    }
}
