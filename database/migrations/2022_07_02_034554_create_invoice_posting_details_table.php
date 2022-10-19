<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicePostingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_posting_details', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_posting_no');
            $table->unsignedBigInteger('item_id');
            $table->integer('quantity');
            $table->decimal('purchase_rate', 10,3);
            $table->integer('vat_rate');
            $table->decimal('vat_amount', 10,3);
            $table->decimal('total_amount', 10,2);
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
        Schema::dropIfExists('invoice_posting_details');
    }
}
