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
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('post');
            $table->unsignedInteger('user')->nullable();
            $table->string('author_name')->nullable();
            $table->string('author_email')->nullable();
            $table->text('content');
            $table->boolean('approved')->default(false);
            
            $table->timestamps();
            
            $table->foreign('post')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('user')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
