<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class TransactionsController extends Controller
{
    public function index(): Response
    {
        $transactions = collect([
            [
                'id' => 1,
                'member' => [
                    'name' => 'Andi Putra Wijaya',
                ],
                'status' => 'Pending',
                'created_at' => '2025-11-11 14:30:00',
                'items' => [
                    [
                        'name' => 'Kopi Arabica 250gr',
                        'qty' => 2,
                        'buy_price' => 20000,
                        'sell_price' => 25000,
                    ],
                    [
                        'name' => 'Gula Aren Premium',
                        'qty' => 1,
                        'buy_price' => 35000,
                        'sell_price' => 40000,
                    ],
                ],
            ],
            [
                'id' => 2,
                'member' => [
                    'name' => 'Arief F-sa Wijaya',
                ],
                'status' => 'Lunas',
                'created_at' => '2025-11-01 13:30:00',
                'items' => [
                    [
                        'name' => 'Teh Hijau Premium',
                        'qty' => 1,
                        'buy_price' => 45000,
                        'sell_price' => 50000,
                    ],
                    [
                        'name' => 'Madu Murni 250ml',
                        'qty' => 1,
                        'buy_price' => 60000,
                        'sell_price' => 65000,
                    ],
                ],
            ],
        ]);

        return Inertia::render('Admin/Transactions/Index', [
            'transactions' => $transactions,
        ]);
    }

    public function verify($id)
    {
        // $transaction = FinancingApplication::findOrFail($id);
        // $transaction->status = 'Lunas';
        // $transaction->save();

        return back()->with('success', 'Transaksi berhasil diverifikasi!');
    }
}
