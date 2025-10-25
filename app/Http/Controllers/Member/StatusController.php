<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\FinancingApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;

class StatusController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $query = FinancingApplication::where('member_user_id', Auth::id())
            ->with('order');

        // Filter Berdasarkan Tanggal
        if ($request->date_filter === '7_days') {
            $query->where('created_at', '>=', Carbon::now()->subDays(7));
        } elseif ($request->date_filter === '30_days') {
            $query->where('created_at', '>=', Carbon::now()->subDays(30));
        } elseif ($request->date_filter === 'custom' && $request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // Filter Berdasarkan Status
        if ($request->status_filter) {
            if ($request->status_filter === 'Tunai') {
                // hanya tampilkan pengajuan tunai yang sudah lunas
                $query->where('status', 'Lunas')
                    ->where(function ($q) {
                        $q->whereNull('tenor')->orWhere('tenor', 0);
                    });
            } elseif ($request->status_filter === 'Cicilan') {
                // tampilkan semua pengajuan cicilan (ada tenor)
                $query->whereNotNull('tenor')
                    ->whereIn('status', ['Approved', 'Pending', 'Rejected', 'Active', 'Lunas']);
            } else {
                $query->where('status', $request->status_filter);
            }
        }

        // Filter Berdasarkan Total Harga
        if ($request->sort_price === 'highest') {
            $query->orderBy('selling_price_total', 'desc');
        } elseif ($request->sort_price === 'lowest') {
            $query->orderBy('selling_price_total', 'asc');
        }

        $applications = $query->get(['id', 'order_id', 'tenor', 'status', 'selling_price_total', 'created_at']);

        return Inertia::render('Member/Status', [
            'applications' => $applications,
            'filters' => [
                'date_filter' => $request->date_filter,
                'status_filter' => $request->status_filter,
                'sort_price' => $request->sort_price,
            ],
        ]);
    }
}
