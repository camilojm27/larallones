<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_identity_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->char('country_code', 2);
            $table->string('document_type', 16);
            $table->string('document_number', 64);
            $table->boolean('is_primary')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->string('verification_source', 64)->nullable();
            $table->timestamps();

            $table->unique(['country_code', 'document_type', 'document_number'], 'uniq_identity_doc');
            $table->index('user_id', 'idx_identity_doc_user');
        });

        DB::statement('CREATE UNIQUE INDEX uniq_identity_doc_primary ON user_identity_documents (user_id) WHERE is_primary = true');
    }

    public function down(): void
    {
        Schema::dropIfExists('user_identity_documents');
    }
};
