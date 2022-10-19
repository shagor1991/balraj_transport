<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('invoice_no');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('truck_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('description');
            $table->string('crusher');
            $table->string('destination');
            $table->integer('qty');
            $table->decimal('rate',10,2);
            $table->decimal('amount',10,2);
            $table->decimal('vat_rate',10,2);
            $table->decimal('vat_amount',10,2);
            $table->date('date');
            $table->integer('is_invoiced')->default(0);
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
        Schema::dropIfExists('supplier_invoice_items');
    }
}
