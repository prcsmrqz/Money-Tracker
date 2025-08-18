<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\TransactionRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\UpdateTransactionRequest;

class TransactionController extends Controller
{
    public function index()
    {
        $categories = auth()->user()->categories()->where('type', 'income')->orderBy('name', 'ASC')->get();
        $expensesCategories = auth()->user()->categories()->where('type', 'expenses')->orderBy('name', 'ASC')->get();
        $savingsAccounts = auth()->user()->savingsAccount()->orderBy('name','ASC')->get();
        $activeTab = session('activeTab', 'income'); 
        
        return view("transaction.index", compact('categories', 'savingsAccounts', 'activeTab', 'expensesCategories'));
    }

    public function store(TransactionRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $type = $data['type'];
        auth()->user()->transactions()->create($data);

        return redirect()->back()->with('success', ucfirst($type) . ' transaction created successfully.')->with('activeTab', $type);
    }


    public function update(Request $request, $id)
    {
        $transaction = auth()->user()->transactions()->findOrFail($id);
        
            $currentUrl = $request->input('url') . '?mode=' . $request->input('mode');

        try {
            $validated = app(UpdateTransactionRequest::class)->setContainer(app())->merge($request->all())->validateResolved();
            
            $data = validator($request->all(), (new UpdateTransactionRequest())->rules())->validate();
            
            $transaction->update($data);

            return redirect($currentUrl)->with('success', 'Transaction updated successfully.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            $currentUrl = $currentUrl ?? route('category.show', $transaction->category_id);
            return redirect($currentUrl)
                ->withErrors($e->validator, 'update_' . $id)
                ->withInput()
                ->with('error_transaction_id', $id);
        }
    }

    public function destroy($id) {
        $transaction = auth()->user()->transactions()->findOrFail($id);

        $transaction->delete();
        
        return redirect()->back()->with('success','Transaction deleted successfully.');
    }
}
