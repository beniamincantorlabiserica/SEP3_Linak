<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('desk_usage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('desk_id');
            $table->integer('position_mm');
            $table->enum('position_type', ['sitting', 'standing']);
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'started_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('desk_usage_logs');
    }
};