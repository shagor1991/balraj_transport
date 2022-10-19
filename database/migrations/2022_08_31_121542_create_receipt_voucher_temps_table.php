<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptVoucherTempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt_voucher_temps', function (Blueprint $table) {
            $table->id();
            $table->string('type')->comment('due or advance');
            $table->unsignedBigInteger('cost_center_id');
            $table->unsignedBigInteger('party_info_id');
            $table->decimal('amount',10,2);
            $table->date('payment_date');
            $table->string('pay_mode');
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
        Schema::dropIfExists('receipt_voucher_temps');
    }
}
