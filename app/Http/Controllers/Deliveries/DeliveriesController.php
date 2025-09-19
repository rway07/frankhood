<?php
declare(strict_types=1);

namespace App\Http\Controllers\Deliveries;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeliveryRequest;
use App\Util\DataFetcher;
use App\Util\DataValidator;
use App\Util\DeliveriesIntegrityUtil;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View as View;
use Throwable;

/**
 *
 */
class DeliveriesController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        $years = DB::select(
            "select distinct strftime('%Y', date) as year
            from receipts
            order by year desc"
        );

        return view(
            'deliveries/index',
            [
                'years' => $years
            ]
        );
    }

    /**
     * @param $year
     * @return JsonResponse
     */
    public function deliveriesList($year): JsonResponse
    {
        $validator = new DataValidator();
        if (!$validator->checkYear($year)) {
            return response()->json(
                [
                    'error' => [
                        'message' => $validator->getReturnMessage()
                    ]
                ]
            );
        }

        try {
            $deliveries = DataFetcher::getDeliveriesData($year);
            $totalAmount = DataFetcher::getDeliveriesTotalAmount($year);
        } catch (Exception $e) {
            return response()->json(
                [
                    'error' => [
                        'message' => $e->getMessage()
                    ]
                ]
            );
        }

        $view = view(
            'deliveries/data',
            [
                'deliveries' => $deliveries,
            ]
        )->render();

        return response()->json(
            [
                'data' => [
                    'rows' => count($deliveries),
                    'totalAmount' => $totalAmount,
                    'view' => $view
                ]
            ]
        );
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $lastDate = DataFetcher::getLastDeliveryDate();

        return view(
            'deliveries/create',
            [
                'lastDate' => $lastDate
            ]
        );
    }

    /**
     * @param DeliveryRequest $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(DeliveryRequest $request): RedirectResponse
    {
        try {
            $validatedData = $request->validated();

            DeliveriesIntegrityUtil::integrityCheck($validatedData);

            DB::beginTransaction();
            DB::table('deliveries')
                ->insert(
                    [
                        'date' => $validatedData['date'],
                        'amount' => $validatedData['amount'],
                        'total' => $validatedData['total'],
                        'remaining' => $validatedData['remaining']
                    ]
                );
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::to('deliveries/index')
                ->withErrors($e->getMessage());
        }

        return Redirect::to('deliveries/index')
            ->with('status', 'Consegna salvata');
    }

    /**
     * @param $year
     * @param $eraseAll
     * @return JsonResponse
     * @throws Throwable
     */
    private function deleteDelivery($year, $eraseAll): JsonResponse
    {
        try {
            DB::beginTransaction();

            $beginDate = $year . '-01-01';
            $endDate = $year . '-12-31';

            if ($eraseAll) {
                $rows = DB::delete(
                    'delete from deliveries
                where date between ? and ?;',
                    [
                        $beginDate,
                        $endDate
                    ]
                );
            } else {
                $rows = DB::delete(
                    'delete from deliveries
                    where id_delivery in (
                        select id_delivery
                        from deliveries
                        where date between ? and ?
                        order by date desc
                        limit 1
                    )',
                    [
                        $beginDate,
                        $endDate
                    ]
                );
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(
                ['error' => ['message' => $e->getMessage()]]
            );
        }

        if ($eraseAll) {
            $message = 'Eliminate tutte le consegne per l\'anno ' . $year;
        } else {
            $message = 'Eliminata ultima consegna per l\'anno ' . $year;
        }

        return response()->json(
            [
                'data' =>
                    [
                        'rows' => $rows,
                        'message' => $message
                    ]
            ]
        );
    }

    /**
     * @param $year
     * @return JsonResponse
     * @throws Throwable
     */
    public function deleteLast($year): JsonResponse
    {
        return $this->deleteDelivery($year, false);
    }

    /**
     * @param $year
     * @return JsonResponse
     * @throws Throwable
     */
    public function deleteAll($year): JsonResponse
    {
        return $this->deleteDelivery($year, true);
    }
}
