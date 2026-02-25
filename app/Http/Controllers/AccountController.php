<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $histories = collect();

        if ($user && Schema::hasTable('calculation_histories') && Schema::hasColumns('calculation_histories', ['user_id', 'kategorija', 'input_payload', 'result_payload'])) {
            $histories = $user->calculationHistories()->latest()->limit(50)->get();
        }

        return view('account.index', [
            'user' => $user,
            'histories' => $histories,
        ]);
    }
}
