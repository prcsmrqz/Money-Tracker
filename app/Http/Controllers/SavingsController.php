<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SavingsAccount;
use Illuminate\Support\Facades\Storage;
use Dotenv\Exception\ValidationException;
use App\Http\Requests\StoreSavingsRequest;
use App\Http\Requests\UpdateSavingsRequest;

class SavingsController extends Controller
{
    public function index() 
    {
        $savingsAccounts = auth()->user()->savingsAccounts()->orderBy('name', 'ASC')->get();
        foreach( $savingsAccounts as $savingsAccount) {
            $savingsAccount->totalSavings = auth()->user()->transactions()
                ->where('savings_account_id', $savingsAccount->id)
                ->sum('amount');
        }

        return view('savings.index', compact('savingsAccounts'));
    }

    public function store(StoreSavingsRequest $request) 
    {
        try {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $savingsData = [];
        
        $savingsData['name'] = $data['name'];
        $savingsData['color'] = $data['color'];
        $savingsData['type'] = $data['type'];
        $savingsData['account_number'] = $data['account_number'];
        $savingsData['icon'] = $request->file('icon') ? $request->file('icon')->store('icons', 'public') : null;
        $savingsData['user_id'] = $data['user_id'];

        $savings = auth()->user()->savingsAccounts()->create($savingsData);

        $transactionData = [];
        $transactionData['date'] = $data['date'];
        $transactionData['amount'] = $data['amount'];
        $transactionData['type'] = $data['transaction_type'];
        $transactionData['savings_account_id'] = $savings->id;
        $transactionData['user_id'] = $data['user_id'];

        auth()->user()->transactions()->create($transactionData);

        return redirect()->back()->with('success', 'Savings account created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
            ->withErrors($e->validator, 'create')
            ->withInput();

        }
    }

    public function update(UpdateSavingsRequest $request, $id)
    {
        $savings = auth()->user()->savingsAccounts()->findOrFail($id);

        try {
            $data = $request->validated();
            if ($request->hasFile('icon')) {
                $data['icon'] = $request->file('icon')->store('icons', 'public');
            } else {
                unset($data['icon']);
            }
            $savings->update($data);
            return redirect()->back()->with('success', 'Savings account updated successfully.');

        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator, 'update_' . $id)
                ->withInput();
        }

    }


    public function destroy ($id)
    {
        $savings = auth()->user()->savingsAccounts()->findOrFail($id);
        if ($savings) {
            if ($savings->icon && Storage::disk('public')->exists($savings->icon)) {
                Storage::disk('public')->delete($savings->icon);
            }
            
            $savings->delete();
            return redirect()->back()->with('success', 'Savings Account deleted successfully.');
        }
        return redirect()->back()->with('error', 'Savings Account not found.');
    }
}
