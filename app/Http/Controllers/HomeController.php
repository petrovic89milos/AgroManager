<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'farms' => 0,
            'animals' => 0,
            'monthly_expense' => 0,
        ];

        return view('home', compact('stats'));
    }
}
