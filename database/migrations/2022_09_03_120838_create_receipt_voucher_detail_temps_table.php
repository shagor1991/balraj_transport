<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptVoucherDetailTempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt_voucher_detail_temps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('receipt_voucher_temp_id');
            $table->unsignedBigInteger('invoice_id');
            $table->string('invoice_no');
            $table->unsignedBigInteger('cost_center_id');
            $table->unsignedBigInteger('party_info_id');
            $table->decimal('invoice_amount',10,2);
            $table->decimal('paid_amount',10,2);
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
        Schema::dropIfExists('receipt_voucher_detail_temps');
    }
}
