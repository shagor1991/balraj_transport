<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchse_details', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_no');
            $table->integer('brand_id');
            $table->integer('group_id')->nullable();
            $table->integer('item_id');
            $table->decimal('purchase_rate', 10, 3);
            $table->integer('quantity');
            $table->string('unit')->nullable();
            $table->string('vat_rate');
            $table->decimal('vat_amount', 10, 3);
            $table->decimal('total_amount', 10, 2);
            $table->string('taxable_supplies')->nullable();
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('purchse_details');
    }
}
