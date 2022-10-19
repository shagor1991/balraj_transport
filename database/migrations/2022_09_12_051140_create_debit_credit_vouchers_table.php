<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebitCreditVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debit_credit_vouchers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('journal_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('cost_center_id');
            $table->unsignedBigInteger('party_info_id');
            $table->unsignedBigInteger('account_head_id');
            $table->string('pay_mode');
            $table->string('type');
            $table->date('date');
            $table->decimal('amount', 10,2);
            $table->string('narration');
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
        Schema::dropIfExists('debit_credit_vouchers');
    }
}
