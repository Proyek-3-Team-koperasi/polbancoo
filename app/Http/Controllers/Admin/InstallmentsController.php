<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class InstallmentsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Installments/Index');
    }

    public function show(): Response
    {
        return Inertia::render('Admin/Installments/Detail');
    }
}

