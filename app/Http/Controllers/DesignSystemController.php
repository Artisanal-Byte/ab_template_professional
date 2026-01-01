<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class DesignSystemController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('DesignSystem');
    }
}
