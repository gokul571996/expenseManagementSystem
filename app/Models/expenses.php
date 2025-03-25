<?php

namespace App\Models;
use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class expenses extends Model {
	protected $table = "expenses";
	protected $primaryKey = "id";
	protected $guarded = [];

}
