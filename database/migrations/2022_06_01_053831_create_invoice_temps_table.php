<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_temps', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('invoice_no')->unique();
            $table->date('date')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('gl_code')->nullable();
            $table->string('project_id')->nullable();
            $table->string('trn_no')->nullable();
            $table->string('pay_mode')->nullable();
            $table->string('pay_terms')->nullable();
            $table->string('due_date')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('address')->nullable();
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
        Schema::dropIfExists('invoice_temps');
    }
}
