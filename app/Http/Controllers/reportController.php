<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\ExpenseLimitCrossedMail;
use Illuminate\Support\Facades\Mail;
use Spatie\Browsershot\Browsershot;
use Carbon\Carbon;
use App\Models\expense_category;
use App\Models\expenses;
use App\Models\User;
use DB;

class reportController extends Controller
{
    public function reports(Request $request)
    {
        $userId = auth()->id();

        $query = expenses::leftJoin('expense_category', 'expenses.expenseCategoryId', '=', 'expense_category.id')
            ->selectRaw('expenses.expenseCategoryId, expense_category.categoryName, expense_category.categorylimit, DATE(expenses.date) as expenseDate, SUM(expenses.expenseAmount) as totalExpenseAmount')
            ->where('expenses.userId', $userId)
            ->where('expenses.sts', 1);

        if ($request->from_date) {
            $query->whereDate('expenses.date', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('expenses.date', '<=', $request->to_date);
        }

        if ($request->category_id) {
            $query->where('expenses.expenseCategoryId', $request->category_id);
        }

        if ($request->amount_min) {
            $query->where('expenses.expenseAmount', '>=', $request->amount_min);
        }

        if ($request->amount_max) {
            $query->where('expenses.expenseAmount', '<=', $request->amount_max);
        }

        $allGroupedExpenses = $query->groupBy('expenses.expenseCategoryId', 'expense_category.categoryName','expense_category.categorylimit', 'expenseDate')
            ->orderBy('expenseDate', 'desc')
            ->get();

        $dates = $allGroupedExpenses->pluck('expenseDate')->unique()->sort()->values();
        $categories = $allGroupedExpenses->pluck('categoryName')->unique()->values();

        $dataMatrix = [];
        foreach ($categories as $category) {
            foreach ($dates as $date) {
                $amount = $allGroupedExpenses
                    ->where('categoryName', $category)
                    ->where('expenseDate', $date)
                    ->pluck('totalExpenseAmount')
                    ->first();
                $dataMatrix[$category][] = $amount ?? 0;
            }
        }

        $categoryTotalsQuery = expenses::leftJoin('expense_category', 'expenses.expenseCategoryId', '=', 'expense_category.id')
            ->selectRaw('expense_category.categoryName, SUM(expenses.expenseAmount) as totalExpense')
            ->where('expenses.userId', $userId)
            ->where('expenses.sts', 1);

        if ($request->from_date) {
            $categoryTotalsQuery->whereDate('expenses.date', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $categoryTotalsQuery->whereDate('expenses.date', '<=', $request->to_date);
        }

        if ($request->category_id) {
            $categoryTotalsQuery->where('expenses.expenseCategoryId', $request->category_id);
        }

        if ($request->amount_min) {
            $categoryTotalsQuery->where('expenses.expenseAmount', '>=', $request->amount_min);
        }

        if ($request->amount_max) {
            $categoryTotalsQuery->where('expenses.expenseAmount', '<=', $request->amount_max);
        }

        $categoryTotals = $categoryTotalsQuery
            ->groupBy('expense_category.categoryName')
            ->orderBy('expense_category.categoryName')
            ->get();

        $categoryNames = $categoryTotals->pluck('categoryName');
        $categoryExpenses = $categoryTotals->pluck('totalExpense');

        $categoriesList = expense_category::activeListByNames($userId)->get();

        return view('reports.reports', compact('allGroupedExpenses', 'dates', 'categories', 'dataMatrix', 'categoryNames', 'categoryExpenses', 'categoriesList', 'request'));
    }

    public function downloadExpensePdf(Request $request)
    {
        $categoryId = $request->query('categoryId');
        $date = $request->query('date');

        $expenses = expenses::where('userId', auth()->id())
            ->where('expenseCategoryId', $categoryId)
            ->whereDate('date', $date)
            ->where('sts', 1)
            ->get();

        $category = expense_category::find($categoryId);

        $totalExpenseAmount = $expenses->sum('expenseAmount');
        $totalExpenseLimit = $category->categorylimit ?? 0;
        $totalBalance = $totalExpenseLimit - $totalExpenseAmount;

        $html = view('reports.expense-report', compact('expenses', 'category', 'date', 'totalExpenseAmount', 'totalExpenseLimit', 'totalBalance'))->render();

        $pdfPath = storage_path('app/public/expense-report-' . now()->timestamp . '.pdf');

        Browsershot::html($html)
            ->format('A4')
            ->margins(10, 10, 10, 10)
            ->showBackground()
            ->savePdf($pdfPath);

        return response()->download($pdfPath)->deleteFileAfterSend();
    }

    public function downloadAllExpensesPdf(Request $request)
    {
        $userId = auth()->id();
        $query = expenses::leftJoin('expense_category', 'expenses.expenseCategoryId', '=', 'expense_category.id')
            ->where('expenses.userId', $userId)
            ->where('expenses.sts', 1);

        if ($request->from_date) {
            $query->whereDate('expenses.date', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('expenses.date', '<=', $request->to_date);
        }

        if ($request->category_id) {
            $query->where('expenses.expenseCategoryId', $request->category_id);
        }

        if ($request->amount_min) {
            $query->where('expenses.expenseAmount', '>=', $request->amount_min);
        }

        if ($request->amount_max) {
            $query->where('expenses.expenseAmount', '<=', $request->amount_max);
        }

        $allGroupedExpenses = $query->selectRaw('expenses.expenseCategoryId, expenses.date, SUM(expenses.expenseAmount) as totalExpenseAmount, expense_category.categoryName, expense_category.categorylimit')
            ->groupBy('expenses.expenseCategoryId', 'expenses.date', 'expense_category.categoryName', 'expense_category.categorylimit')
            ->orderBy('expenses.date', 'desc')
            ->get();

        $totalExpenseAmount = $allGroupedExpenses->sum('totalExpenseAmount');
        $totalExpenseLimit = $allGroupedExpenses->sum('categorylimit');
        $totalBalance = $totalExpenseLimit - $totalExpenseAmount;

        $html = view('reports.all_expense_pdf', compact('allGroupedExpenses', 'totalExpenseAmount', 'totalExpenseLimit', 'totalBalance'))->render();
        $pdfPath = storage_path('app/public/all-expense-report-' . now()->timestamp . '.pdf');

        Browsershot::html($html)
            ->format('A4')
            ->showBackground()
            ->save($pdfPath);

        return response()->download($pdfPath)->deleteFileAfterSend();
    }

    

}
