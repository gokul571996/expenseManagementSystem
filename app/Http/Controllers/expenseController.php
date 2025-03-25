<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\ExpenseLimitCrossedMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\expense_category;
use App\Models\expenses;
use App\Models\User;

class expenseController extends Controller
{
    public function addExpense()
    {
        $userId = Auth::id();
        $categoryList = expense_category::activeListByNames($userId)->get();
        return view('addExpense', compact('categoryList'));
    }

    public function expenseSave(Request $request)
    {
        $userId = Auth::id();
        $category = expense_category::find($request->expenseCategory);
        $user = User::find($userId);
        expenses::create([
            'expenseCategoryId' => $request->expenseCategory,
            'expenseAmount' => $request->amount,
            'date' => $request->expenseDate,
            'description' => $request->description ?? '',
            'userId' => $userId,
        ]);

        $totalExpenses = expenses::where('userId', $userId)
        ->where('expenseCategoryId', $request->expenseCategory)
        ->where('date', $request->expenseDate)
        ->sum('expenseAmount');

        if($category->categorylimit < $totalExpenses){
            Mail::to($user->email)->send(new ExpenseLimitCrossedMail(
                $category->categoryName,
                $category->categorylimit,
                $totalExpenses
            ));
        }
        return redirect('/addExpense')->with('success', 'Expense saved successfully!');
    }

    public function allExpense(Request $request)
    {
        $userId = Auth::id();

        $allExpenses = expenses::leftJoin('expense_category', 'expenses.expenseCategoryId', '=', 'expense_category.id')
            ->where('expenses.userId', $userId)
            ->where('expenses.sts', 1)
            ->select(
                'expenses.id',
                'expenses.expenseAmount',
                'expenses.date',
                'expenses.description',
                'expense_category.categoryName',
                'expense_category.categorylimit'
            )
            ->orderBy('expenses.date', 'desc')
            ->get();

        return view('allExpense', compact('allExpenses'));
       
    }

    public function expenseDelete($id)
    {
        try {
            $category = expenses::findOrFail($id);
            $category->sts = 0;
            $category->save();

            return redirect()->back()->with('success', 'Expense Record deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong while disabling the category.');
        }
    }

}
