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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('key');                // e.g. "auth.login.title" or "home.welcome"
            $table->string('locale', 10);         // e.g. "en", "fr", "es"
            $table->text('value')->nullable();    // the translated text (null allowed if not provided yet)
            $table->text('notes')->nullable();    // optional maintainer notes/context
            $table->timestamps();

            $table->unique(['key','locale']);     // ensure uniqueness per locale
            $table->index('locale');
            $table->index('key');
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();     // e.g. mobile, desktop, web
            $table->timestamps();
        });

        Schema::create('tag_translation', function (Blueprint $table) {
            $table->foreignId('translation_id')->constrained('translations')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->primary(['translation_id','tag_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tag_translation');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('translations');
    }
};
