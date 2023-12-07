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
        Schema::create('email_template_pivot_email_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('email_template_id');
            $table->foreign('email_template_id')->references('id')->on('email_templates')->onDelete('cascade');
            $table->unsignedSmallInteger('email_template_category_id');
            $table->foreign('email_template_category_id')->references('id')->on('email_template_categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_template_pivot_email_categories');
    }
};
