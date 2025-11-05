<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class SystemConfigurationController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('SuperAdmin/SystemConfiguration/Index');
    }
}

