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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            // F - K
            $table->unsignedBigInteger('user_id');

            $table->string('title', 100);
            $table->string('slug', 150);
            $table->string('excerpt', 150);
            $table->longText('description');
            $table->boolean('is_published');
            $table->string('min_to_read', 10);

            // Relationship
            $table->foreign('user_id')->references('id')->on('users')
                ->restrictOnDelete()
                ->restrictOnUpdate();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
