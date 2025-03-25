<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseCategoryTable extends Migration
{
    public function up()
    {
        Schema::create('expense_category', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId');
            $table->string('categoryName');
            $table->decimal('categorylimit', 15, 2)->nullable();
            $table->tinyInteger('sts')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('expense_category');
    }
}