<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Member/Checkout');
    }

    public function success(): Response
    {
        return Inertia::render('Member/CheckoutSuccess');
    }
}
