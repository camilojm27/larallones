<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile_field_definitions', function (Blueprint $table) {
            $table->string('field_key', 64)->primary();
            $table->string('data_type', 32);
            $table->jsonb('validation_rules')->default(DB::raw("'{}'::jsonb"));
            $table->boolean('is_pii')->default(false);
            $table->string('i18n_label_key', 128);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE profile_field_definitions ADD COLUMN countries CHAR(2)[] NULL');
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_field_definitions');
    }
};
