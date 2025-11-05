<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class InstallmentsController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Admin/Installments/Index');
    }
}

