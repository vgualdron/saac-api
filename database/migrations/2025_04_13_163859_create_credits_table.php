<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->timestamp('credit_date');
            $table->timestamp('approved_date');
            $table->unsignedBigInteger('associated_id');
            $table->unsignedBigInteger('creditline_id');
            $table->decimal('annual_interest', 5, 2);
            $table->decimal('credit_interest', 6, 3);
            $table->integer('debtor_insurance');
            $table->integer('credit_insurance');
            $table->string('credit_term');
            $table->integer('credit_value');
            $table->integer('quota_value');
            $table->string('request_observation', 250);
            $table->string('status', 20);
            $table->unsignedBigInteger('created_by');
            $table->string('otp', 6);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};
