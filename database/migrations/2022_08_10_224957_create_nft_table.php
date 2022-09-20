<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('nfts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('nft_token_id')->unique();
            $table->foreignId('collection_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('file_path');
            $table->enum('file_type', ['image', 'audio', 'video'])->default('image');
            $table->string('title');
            $table->string('description');
            $table->string('creator_address');
            $table->unsignedDecimal('price');
            $table->enum('status', ['published', 'pending', 'canceled', 'deleted'])->default('published');
            $table->boolean('is_for_sale')->default(false);
            $table->date('sale_end_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nfts');
    }
};
