<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_acount_id');
            $table->unsignedBigInteger('account_head_id');
            $table->unsignedBigInteger('party_info_id');
            $table->decimal('taxable_amount',11,2);
            $table->decimal('vat_amount',11,2);
            $table->decimal('total_amount',11,2);
            $table->string('voucher_copy');
            $table->string('date');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenses');
    }
}
