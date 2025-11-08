<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutCreditController extends Controller
{
    private const SESSION_KEY = 'member.checkout.credit.payload';

    public function __invoke(Request $request): Response
    {
        $payload = $this->resolvePayload($request);

        return Inertia::render('Member/CheckoutCredit', $payload);
    }

    private function resolvePayload(Request $request): array
    {
        if ($request->isMethod('post')) {
            $payload = $this->buildPayloadFromRequest($request);
            session([self::SESSION_KEY => $payload]);

            return $payload;
        }

        return session(self::SESSION_KEY, $this->defaultPayload());
    }

    private function buildPayloadFromRequest(Request $request): array
    {
        $cartItems = $request->input('cartItems', []);

        if (!is_array($cartItems) || empty($cartItems)) {
            $cartItems = $this->defaultCartItems();
        }

        $pointsPerRupiah = (int) $request->input('pointsPerRupiah', 100000);

        return [
            'cartItems' => $cartItems,
            'pointsPerRupiah' => $pointsPerRupiah,
            'estimatedPoints' => (int) ($request->input('estimatedPoints') ?? $this->calculateEstimatedPoints($cartItems, $pointsPerRupiah)),
            'totalAmount' => (int) ($request->input('totalAmount') ?? $this->calculateTotal($cartItems)),
            'paymentMethod' => $request->input('paymentMethod', 'credit'),
        ];
    }

    private function defaultPayload(): array
    {
        $cartItems = $this->defaultCartItems();

        return [
            'cartItems' => $cartItems,
            'pointsPerRupiah' => 100000,
            'estimatedPoints' => $this->calculateEstimatedPoints($cartItems, 100000),
            'totalAmount' => $this->calculateTotal($cartItems),
            'paymentMethod' => 'credit',
        ];
    }

    private function defaultCartItems(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'lorem ipsum dolor sit amet consectetur adipisicing elit',
                'price' => 100000,
                'quantity' => 222,
                'marginCredits' => 10000,
                'imageUrl' => 'https://images.unsplash.com/photo-1630563451961-ac2ff27616ab?auto=format&fit=crop&q=80&w=687',
            ],
            [
                'id' => 2,
                'name' => 'Produk B',
                'price' => 150000,
                'quantity' => 1,
                'marginCredits' => 20000,
                'imageUrl' => 'https://images.unsplash.com/photo-1503602642458-232111445657?auto=format&fit=crop&q=80&w=687',
            ],
        ];
    }

    private function calculateTotal(array $items): int
    {
        return collect($items)->reduce(function ($carry, $item) {
            $price = (int) ($item['price'] ?? 0);
            $quantity = (int) ($item['quantity'] ?? 1);
            return $carry + ($price * max($quantity, 1));
        }, 0);
    }

    private function calculateEstimatedPoints(array $items, int $pointsPerRupiah): int
    {
        if ($pointsPerRupiah <= 0) {
            return 0;
        }

        return intdiv($this->calculateTotal($items), $pointsPerRupiah);
    }
}
