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
        Schema::create('test_metas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained()->cascadeOnDelete();
            $table->string('purpose')->nullable();
            $table->string('target_age_group')->nullable();
            $table->string('test_type')->nullable();
            $table->string('approximate_duration')->nullable();
            $table->string('required_tools')->nullable();
            $table->string('analysis_method')->nullable();
            $table->string('reliability_coefficient')->nullable();
            $table->string('validity')->nullable();
            $table->string('language_requirement')->nullable();
            $table->string('iq_estimation_possibility')->nullable();
            $table->text('main_applications')->nullable();
            $table->text('strengths')->nullable();
            $table->text('limitations')->nullable();
            $table->text('advanced_versions')->nullable();
            $table->text('advantages_of_execution')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_metas');
    }
};
