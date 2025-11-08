<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinancingApplication;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    // tampilkan daftar transaksi tunai
    public function index(): Response
    {
        $transactions = FinancingApplication::with(['order', 'member'])
            ->where(function ($q) {
                $q->whereNull('tenor')->orWhere('tenor', 0);
            })
            ->get(['id', 'order_id', 'member_user_id', 'status', 'selling_price_total', 'created_at']);

        return Inertia::render('Admin/Transactions/Index', [
            'transactions' => $transactions,
        ]);
    }

    // ubah status transaksi jadi lunas
    public function verify($id)
    {
        $transaction = FinancingApplication::findOrFail($id);
        $transaction->status = 'Lunas';
        $transaction->save();

        return back();
    }
}
