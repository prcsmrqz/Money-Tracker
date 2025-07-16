<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function updateCurrency(Request $request)
    {
        $request->validate([
            'currency' => 'required|string',
        ]);

        $currency = json_decode($request->input('currency'), true);

        if (!is_array($currency) || !isset($currency['code'], $currency['symbol'])) {
            return redirect()->back()->withErrors(['currency' => 'Invalid currency selected.']);
        }

        $user = auth()->user();
        $user->currency_code = $currency['code'];
        $user->currency_symbol = $currency['symbol'];
        $user->save();

        return redirect()->back()->with('status', 'Currency updated successfully!');
    }
}
