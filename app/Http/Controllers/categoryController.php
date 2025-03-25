<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\expense_category;

class categoryController extends Controller
{
    public function expenseCategory()
    {
        $userId = Auth::id();
        $categoryList = expense_category::active($userId)->get();
        return view('expenceCategoryList', compact('categoryList'));
    }

    public function categorySave(Request $request)
    {
        $exists = expense_category::category($request->categoryName)->exists();

        if ($exists) {
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Category name already exists!']);
            }
            return redirect('/expense/category')->with('error', 'Category name already exists!');
        }

        expense_category::create([
            'categoryName' => $request->categoryName,
            'categorylimit' => $request->categorylimit,
            'userId' => auth()->id(),
        ]);

        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Category added successfully!']);
        }

        return redirect('/expense/category')->with('success', 'Category added successfully!');
    }

    public function updateCategory(Request $request, $id)
    {
        $category = expense_category::find($id);

        if (!$category) {
            return response()->json(['status' => 'error', 'message' => 'Category not found.']);
        }

        $exists = expense_category::category($request->categoryName)->where('id',"!=",$id)->exists();

        if ($exists) {
            return response()->json(['status' => 'error', 'message' => 'Category name already exists!']);
        }

        $category->categoryName = $request->categoryName;
        $category->categorylimit = $request->categorylimit;
        $category->save();

        return response()->json(['status' => 'success', 'message' => 'Category updated successfully!']);
    }

    public function categoryDelete($id)
    {
        try {
            $category = expense_category::findOrFail($id);
            $category->sts = 0;
            $category->save();

            return redirect()->back()->with('success', 'Category deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong while disabling the category.');
        }
    }

}
