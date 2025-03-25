<?php

namespace App\Models;
use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class expense_category extends Model {
	protected $table = "expense_category";
	protected $primaryKey = "id";
	protected $guarded = [];

	public function scopeActive($query,$userId) {
		return $query->orderBy('expense_category.id', 'desc')->where('expense_category.sts', 1)->where('expense_category.userId',$userId);
	}

    public function scopeCategory($query,$categoryName) {
		return $query->where('expense_category.sts', 1)->where('userId', auth()->id())->where('categoryName', $categoryName);
	}

    public function scopeActiveListByNames($query,$userId) {
		return $query->orderBy('expense_category.categoryName', 'asc')->where('expense_category.sts', 1)->where('expense_category.userId',$userId);
	}
}
