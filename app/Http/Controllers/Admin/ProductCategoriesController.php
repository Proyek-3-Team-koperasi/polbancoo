<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductCategoriesController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Admin/ProductCategories/Index');
    }

    public function index(): Response
    {
        return Inertia::render('Admin/ProductCategories/Index');
    }

    public function create(): Response
    {
        return Inertia::render('Admin/ProductCategories/Form', [
            'intent' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        dd($request);
    }

    public function edit(): Response
    {
        return Inertia::render('Admin/ProductCategories/Form', [
            'intent' => 'edit',
        ]);
    }

    public function update(Request $request)
    {
        dd($request);
    }
}
