<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerTth;
use App\Models\CustomerTthDetail;
use App\Models\MobileConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
 
    public function index(Request $request)
    {
        try {
            $name = $request->query('name');

            $query = Customer::query();
            if ($name) {
                $query->where('Name', 'like', "%{$name}%");
            }

            $customers = $query
                ->with(['gifts' => function ($q) {
                    $q->orderBy('DocDate', 'asc')->with('detail');
                }])
                ->orderBy('Name')
                ->get();


            return response()->json([
                'success' => true,
                'data' => $customers,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching customers: ' . $e->getMessage()
            ], 500);
        }
    }

    public function listCustomer(){

        $customer = Customer::select('name','CustID')->get();

        return response()->json([
                'success' => true,
                'data' => $customer,
            ]);
    }

    public function show(Customer $customer)
    {
        $customer = $customer::with(['gifts.detail'])->find($customer->CustID);
        return response()->json([
            'success' => true,
            'data' => $customer,
        ]);
    }

    public function confirm(Request $request, string $custId)
    {
        $request->validate([
            'action' => 'required|in:accept,reject',
            'reason' => 'nullable|string',
        ]);

        $action = $request->input('action');
        $reason = $request->input('reason');
        $now = Carbon::now();

        try {
            $response = DB::transaction(function () use ($custId, $action, $reason, $now) {
                $rows = CustomerTth::where('CustID', $custId)->lockForUpdate()->get();

                if ($rows->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Customer documents not found.'
                    ], 404);
                }

                if ($action === 'accept') {
                    foreach ($rows as $row) {
                        $row->Received = 1;
                        $row->ReceivedDate = $now;
                        $row->FailedReason = null;
                        $row->save();
                    }
                    return response()->json([
                        'success' => true,
                        'message' => 'Gifts accepted for the store.',
                    ]);
                }

                $hasAccepted = $rows->contains(function ($r) {
                    return (int) $r->Received === 1;
                });
                if ($hasAccepted) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Store already accepted. Cannot change to rejected.',
                    ], 409);
                }

                if (!$reason) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Reason is required for rejection.'
                    ], 422);
                }

                foreach ($rows as $row) {
                    $row->Received = 0;
                    $row->ReceivedDate = null;
                    $row->FailedReason = $reason;
                    $row->save();
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Gifts rejected for the store.',
                ]);
            }, 3);

            return $response;
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Confirmation failed: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function summary()
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->buildGiftSummary(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error building summary: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function buildGiftSummary(): array
    {
        $config = MobileConfig::where('Name', 'SUMMARY TTH')->first();

        $items = [];
        if ($config && $config->Value) {
            $items = array_map('trim', explode('|', $config->Value));
        }

        if (empty($items)) {

            $items = CustomerTthDetail::query()->distinct()->pluck('Jenis')->filter()->values()->all();
        }

        $totals = CustomerTthDetail::selectRaw('Jenis, SUM(COALESCE(Qty,0)) as total')
            ->whereIn('Jenis', $items)
            ->groupBy('Jenis')
            ->pluck('total', 'Jenis');

        $mapUnit = function (string $name): string {
            return str_starts_with($name, 'Emas') ? 'Buah' : (str_starts_with($name, 'Voucher') ? 'Lembar' : '');
        };

        $result = [];
        $total =0;
        foreach ($items as $name) {
           $total += (int) ($totals[$name] ?? 0);
            $result[] = [
                'name' => $name,
                'unit' => $mapUnit($name),
                'qty' => (int) ($totals[$name] ?? 0),

            ];
        }
        $result['total']= $total;

        return $result;
    }
}
