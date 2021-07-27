<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function show(Request $request)
    {
        return Inertia::render('Admin/Show', [
            //
        ]);
    }
}
