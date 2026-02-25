<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('calculation_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('kategorija', 40);
            $table->json('input_payload');
            $table->json('result_payload');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calculation_histories');
    }
};
