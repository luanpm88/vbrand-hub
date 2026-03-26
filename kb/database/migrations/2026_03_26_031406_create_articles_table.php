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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('body_markdown');
            $table->longText('body_html');
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('author_name')->default('AcelleMail Team');
            $table->string('author_avatar')->nullable();
            $table->string('author_bio')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('status', 20)->default('draft');
            $table->unsignedInteger('reading_time')->default(0);
            $table->unsignedBigInteger('views_count')->default(0);
            $table->string('content_type', 30)->default('tutorial');
            $table->string('difficulty', 20)->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('category_id');
            $table->index('published_at');
            $table->index('content_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
