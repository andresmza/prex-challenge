<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->string('gif_id', 100);
            $table->string('alias', 255);
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['user_id', 'gif_id']);
            $table->unique(['user_id', 'alias']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
