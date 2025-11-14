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
    private $useDummy = true; // Ubah ke false jika ingin pakai database

    // ============================================================
    //                        LIST HALAMAN STATUS
    // ============================================================
    public function __invoke(Request $request): Response
    {
        // ============================================================
        //                         DUMMY DATA LIST
        // ============================================================
        if ($this->useDummy) {
            $applications = collect([
                [
                    'id' => 1,
                    'status' => 'Pending',
                    'created_at' => '2025-01-10 12:00:00',
                    'selling_price_total' => 125000,
                    'tenor' => 0,
                    'approval_date' => null,
                    'monthly_installment' => null,
                    'total_items_count' => 3,
                    'order' => [
                        'product_name' => 'Paket Sembako',
                        'payment_method' => 'Tunai',
                        'product_image' => '/img/no-image.png',
                        'items' => [
                            ['name' => 'Kopi Arabica 250gr', 'qty' => 2, 'sell_price' => 25000, 'image' => '/img/no-image.png'],
                            ['name' => 'Gula Aren Premium', 'qty' => 1, 'sell_price' => 35000, 'image' => '/img/no-image.png'],
                            ['name' => 'Teh Hijau Premium', 'qty' => 1, 'sell_price' => 45000, 'image' => '/img/no-image.png'],
                        ],
                    ],
                ],
                [
                    'id' => 2,
                    'status' => 'Active',
                    'created_at' => '2025-01-01 10:00:00',
                    'selling_price_total' => 600000,
                    'tenor' => 3,
                    'approval_date' => '2025-01-01 00:00:00',
                    'monthly_installment' => 200000,
                    'total_items_count' => 2,
                    'order' => [
                        'product_name' => 'Paket Snack Kantor',
                        'payment_method' => 'Murabahah',
                        'product_image' => '/img/no-image.png',
                        'items' => [
                            ['name' => 'Keripik Balado', 'qty' => 3, 'sell_price' => 30000, 'image' => '/img/no-image.png'],
                            ['name' => 'Biskuit Coklat', 'qty' => 2, 'sell_price' => 20000, 'image' => '/img/no-image.png'],
                        ],
                    ],
                ],
            ]);

            return Inertia::render('Member/Status', [
                'applications' => $applications,
                'filters' => [
                    'date_filter' => $request->date_filter,
                    'status_filter' => $request->status_filter,
                    'sort_price' => $request->sort_price,
                ],
            ]);
        }

        // ============================================================
        //                         DATA ASLI DATABASE
        // ============================================================
        $query = FinancingApplication::where('member_user_id', Auth::id())
            ->with(['order', 'order.items']);

        // filter tanggal
        if ($request->date_filter === '7_days') {
            $query->where('created_at', '>=', Carbon::now()->subDays(7));
        } elseif ($request->date_filter === '30_days') {
            $query->where('created_at', '>=', Carbon::now()->subDays(30));
        } elseif ($request->date_filter === 'custom' && $request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // filter status
        if ($request->status_filter) {
            if ($request->status_filter === 'Tunai') {
                $query->where('status', 'Lunas')
                      ->where(fn($q) => $q->whereNull('tenor')->orWhere('tenor', 0));
            } elseif ($request->status_filter === 'Cicilan') {
                $query->whereNotNull('tenor')
                      ->whereIn('status', ['Pending', 'Active', 'Rejected', 'Lunas']);
            } else {
                $query->where('status', $request->status_filter);
            }
        }

        // sort
        if ($request->sort_price === 'highest') {
            $query->orderBy('selling_price_total', 'desc');
        } elseif ($request->sort_price === 'lowest') {
            $query->orderBy('selling_price_total', 'asc');
        }

        // mapping data
        $applications = $query->get()->map(function ($app) {
            $order = $app->order;
            $items = $order?->items?->map(fn($it) => [
                'name' => $it->name ?? $it->product_name ?? '-',
                'qty' => (int) ($it->qty ?? 1),
                'sell_price' => (float) ($it->sell_price ?? 0),
                'image' => $it->image ?? '/img/no-image.png',
            ])->toArray() ?? [];

            $sellingTotal = $app->selling_price_total ?: array_reduce($items, fn($sum, $it) => $sum + $it['qty'] * $it['sell_price'], 0);

            return [
                'id' => $app->id,
                'status' => $app->status,
                'created_at' => $app->created_at?->toDateTimeString(),
                'selling_price_total' => $sellingTotal,
                'total_items_count' => count($items),
                'tenor' => $app->tenor,
                'approval_date' => $app->approval_date?->toDateTimeString(),
                'monthly_installment' => $app->monthly_installment,
                'order' => [
                    'items' => $items,
                    'product_name' => $order?->product_name,
                    'product_image' => $order?->product_image ?? ($items[0]['image'] ?? '/img/no-image.png'),
                    'payment_method' => $order?->payment_method,
                ],
            ];
        });

        return Inertia::render('Member/Status', [
            'applications' => $applications,
            'filters' => [
                'date_filter' => $request->date_filter,
                'status_filter' => $request->status_filter,
                'sort_price' => $request->sort_price,
            ],
        ]);
    }

    // ============================================================
    //                          DETAIL TRANSAKSI
    // ============================================================
    public function show($id): Response
    {
        // ============================================================
        //                       DUMMY UNTUK DETAIL
        // ============================================================
        if ($this->useDummy) {

            $dummyList = [
                1 => [
                    'id' => 1,
                    'status' => 'Pending',
                    'created_at' => '2025-01-10 12:00:00',
                    'selling_price_total' => 125000,
                    'tenor' => 0,
                    'approval_date' => null,
                    'monthly_installment' => null,
                    'order' => [
                        'product_name' => 'Paket Sembako',
                        'payment_method' => 'Tunai',
                        'product_image' => '/img/no-image.png',
                        'items' => [
                            ['name'=>'Kopi Arabica 250gr','qty'=>2,'sell_price'=>25000,'image'=>'/img/no-image.png'],
                            ['name'=>'Gula Aren Premium','qty'=>1,'sell_price'=>35000,'image'=>'/img/no-image.png'],
                            ['name'=>'Teh Hijau Premium','qty'=>1,'sell_price'=>45000,'image'=>'/img/no-image.png'],
                        ]
                    ]
                ],

                2 => [
                    'id' => 2,
                    'status' => 'Active',
                    'created_at' => '2025-01-01 10:00:00',
                    'selling_price_total' => 600000,
                    'tenor' => 3,
                    'approval_date' => '2025-01-01 00:00:00',
                    'monthly_installment' => 200000,
                    'order' => [
                        'product_name' => 'Paket Snack Kantor',
                        'payment_method' => 'Murabahah',
                        'product_image' => '/img/no-image.png',
                        'items' => [
                            ['name'=>'Keripik Balado','qty'=>3,'sell_price'=>30000,'image'=>'/img/no-image.png'],
                            ['name'=>'Biskuit Coklat','qty'=>2,'sell_price'=>20000,'image'=>'/img/no-image.png'],
                        ]
                    ]
                ],
            ];

            // Ambil sesuai ID, fallback ke ID 1
            $transaction = $dummyList[$id] ?? $dummyList[1];

            return Inertia::render('Member/TransactionDetail', [
                'transaction' => $transaction,
            ]);
        }

        // ============================================================
        //                   DATA DETAIL DARI DATABASE
        // ============================================================
        $transaction = FinancingApplication::with(['order', 'order.items'])
            ->where('member_user_id', Auth::id())
            ->findOrFail($id);

        $order = $transaction->order;

        $items = $order?->items?->map(fn($it) => [
            'name' => $it->name ?? '-',
            'qty' => (int) ($it->qty ?? 1),
            'sell_price' => (float) ($it->sell_price ?? 0),
            'image' => $it->image ?? '/img/no-image.png',
        ])->toArray() ?? [];

        $total = $transaction->selling_price_total ?: array_reduce($items, fn($sum, $it) => $sum + $it['qty'] * $it['sell_price'], 0);

        return Inertia::render('Member/TransactionDetail', [
            'transaction' => [
                'id' => $transaction->id,
                'status' => $transaction->status,
                'created_at' => $transaction->created_at?->toDateTimeString(),
                'selling_price_total' => $total,
                'tenor' => $transaction->tenor,
                'approval_date' => $transaction->approval_date?->toDateTimeString(),
                'monthly_installment' => $transaction->monthly_installment,
                'order' => [
                    'payment_method' => $order?->payment_method,
                    'product_image' => $order?->product_image ?? ($items[0]['image'] ?? '/img/no-image.png'),
                    'product_name' => $order?->product_name,
                    'items' => $items,
                ],
            ],
        ]);
    }
}
