<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('contact_id')->nullable()->index();
            $table->string('property_code')->nullable()->index();
            $table->string('seller_commission_code')->nullable()->index();
            $table->schemalessAttributes('meta');
            $table->string('state')->nullable();
            $table->timestamp('consulted_at')->nullable();
            $table->timestamp('availed_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('onboarded_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('payment_failed_at')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('idled_at')->nullable();
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamp('prequalified_at')->nullable();
            $table->timestamp('qualified_at')->nullable();
            $table->timestamp('not_qualified_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamp('disapproved_at')->nullable();
            $table->timestamp('overridden_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });
    }
};
