<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profile_data', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('field_key', 64);
            $table->jsonb('value');
            $table->timestamp('updated_at')->useCurrent();

            $table->primary(['user_id', 'field_key']);
            $table->foreign('field_key')
                ->references('field_key')
                ->on('profile_field_definitions')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profile_data');
    }
};
