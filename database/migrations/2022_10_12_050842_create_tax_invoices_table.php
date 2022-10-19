<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_no');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('project_id');
            $table->date('date');
            $table->string('pay_mode');
            $table->decimal('amount',10,2);
            $table->decimal('vat_amount',10,2);
            $table->decimal('paid_amount',10,2);
            $table->decimal('due_amount',10,2);
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
        Schema::dropIfExists('tax_invoices');
    }
}
