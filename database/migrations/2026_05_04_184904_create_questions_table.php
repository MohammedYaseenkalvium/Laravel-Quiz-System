<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->string('type'); // binary, single_choice, multiple_choice, number, text
            $table->text('question_text');
            $table->string('image_path')->nullable();   // image upload
            $table->string('video_url')->nullable();    // YouTube URL
            $table->integer('marks')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('questions');
    }
};