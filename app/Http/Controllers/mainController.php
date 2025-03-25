<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\expense_category;
use App\Models\expenses;
use App\Models\User;

class mainController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('login');
        }
    }

    public function dashboard()
    {
        $userId = auth()->id();
        $today = now()->toDateString();

        $categoryExpenses = expenses::select('expenses.expenseCategoryId', \DB::raw('SUM(expenseAmount) as total'))
            ->where('expenses.userId', $userId)
            ->whereDate('expenses.date', $today)
            ->groupBy('expenses.expenseCategoryId')
            ->get()
            ->keyBy('expenseCategoryId');

        $categoryList = expense_category::activeListByNames($userId)->get();

        return view('dashboard', compact('categoryExpenses', 'categoryList'));
    }

}
