<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_invoices', function (Blueprint $table) {
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
            $table->string('invoice_scan');
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
        Schema::dropIfExists('supplier_invoices');
    }
}
