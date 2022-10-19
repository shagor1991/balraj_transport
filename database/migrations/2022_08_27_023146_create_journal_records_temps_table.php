<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalRecordsTempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal_records_temps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('journal_temp_id');
            $table->unsignedBigInteger('project_details_id');
            $table->unsignedBigInteger('cost_center_id');
            $table->unsignedBigInteger('party_info_id')->nullable();
            $table->string('journal_no');
            $table->unsignedBigInteger('account_head_id');
            $table->unsignedBigInteger('master_account_id');
            $table->string('account_head');
            $table->decimal('amount',10,2);
            $table->string('transaction_type');
            $table->date('journal_date');
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
        Schema::dropIfExists('journal_records_temps');
    }
}
