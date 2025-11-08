<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class TransactionsController extends Controller
{
    // tampilkan daftar transaksi tunai
    public function index(): Response
    {
        // Dummy data sementara
        $transactions = collect([
            [
                'id' => 1,
                'order' => [
                    'product_image' => '/img/no-image.png',
                    'product_name' => 'Kopi Arabica 250gr',
                ],
                'member' => [
                    'name' => 'Andi Putra Wijaya',
                ],
                'status' => 'Pending',
                'selling_price_total' => 45000,
                'created_at' => now(),
            ],
            [
                'id' => 2,
                'order' => [
                    'product_image' => '/img/no-image.png',
                    'product_name' => 'Teh Hijau Premium',
                ],
                'member' => [
                    'name' => 'ARief F-sa Wijaya',
                ],
                'status' => 'Lunas',
                'selling_price_total' => 55000,
                'created_at' => now(),
            ],
        ]);

        return Inertia::render('Admin/Transactions/Index', [
            'transactions' => $transactions,
        ]);
    }

    // ubah status transaksi jadi lunas
    public function verify($id)
    {
        // $transaction = FinancingApplication::findOrFail($id);
        // $transaction->status = 'Lunas';
        // $transaction->save();

        return back()->with('success', 'Transaksi berhasil diverifikasi!');
    }
}
