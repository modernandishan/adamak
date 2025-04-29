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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->enum('relationship', [
                'مادر',
                'پدر',
                'برادر',
                'خواهر',
                'خاله',
                'دایی',
                'عمه',
                'عمو',
                'پدربرزگ',
                'مادربزرگ',
                'سرپرست',
                'سایر',
            ])->nullable();

            $table->enum('gender', [
                'مرد', 'زن'
            ])->nullable();

            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->string('postal_code')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
