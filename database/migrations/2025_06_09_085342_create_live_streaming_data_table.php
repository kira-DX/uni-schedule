<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('live_streaming_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('channel_id', 32);
            $table->string('video_id', 24);
            $table->string('title', 255)->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->dateTime('scheduled_time')->nullable();
            $table->enum('live_status', ['none', 'upcoming', 'live', 'completed'])->default('none');
            $table->enum('type', ['video', 'short', 'live'])->default('video');
            $table->timestamp('created_at')->useCurrent();
            $table->foreign('channel_id')->references('channel_id')->on('members')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_streaming_data');
    }
};
