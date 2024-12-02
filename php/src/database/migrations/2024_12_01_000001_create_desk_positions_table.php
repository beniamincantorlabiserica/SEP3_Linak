<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('desk_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('desk_id');
            $table->string('name');
            $table->integer('position_mm');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('desk_positions');
    }
};
