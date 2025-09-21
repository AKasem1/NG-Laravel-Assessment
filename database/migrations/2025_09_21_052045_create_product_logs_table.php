<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->enum('action', ['created', 'updated', 'deleted']);
            $table->unsignedBigInteger('changed_by')->nullable(); // Admin user ID
            $table->json('changes')->nullable(); // Store old vs new values
            $table->timestamps();
            
            $table->foreign('changed_by')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_logs');
    }
};
