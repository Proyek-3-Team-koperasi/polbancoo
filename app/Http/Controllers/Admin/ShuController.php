<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShuController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Shu/Index');
    }

    public function calculateKeuntungan(Request $request): Response
    {
        // return dd($request);
        return Inertia::render('Admin/Shu/Kalkulator');
    }
}

