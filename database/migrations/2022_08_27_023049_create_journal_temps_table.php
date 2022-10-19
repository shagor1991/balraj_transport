<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalTempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal_temps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('journal_no')->unique();
            $table->date('date');
            $table->string('invoice_no');
            $table->unsignedBigInteger('cost_center_id');
            $table->unsignedBigInteger('party_info_id');
            $table->unsignedBigInteger('account_head_id');
            $table->boolean('authorized')->default(0);
            $table->boolean('approved')->default(0);
            $table->string('pay_mode');
            $table->decimal('amount', 12,2);
            $table->integer('tax_rate');
            $table->decimal('vat_amount', 12,2);
            $table->decimal('total_amount', 12,2);
            $table->string('narration');
            $table->string('voucher_scan');
            $table->softDeletes();
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
        Schema::dropIfExists('journal_temps');
    }
}
