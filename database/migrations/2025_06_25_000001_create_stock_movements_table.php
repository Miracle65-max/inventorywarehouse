<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id('movement_id');
            $table->unsignedBigInteger('item_id');
            $table->string('movement_type');
            $table->integer('quantity_changed');
            $table->integer('new_total_quantity');
            $table->unsignedBigInteger('user_id');
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->foreign('item_id')->references('item_id')->on('items');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
    public function down() {
        Schema::dropIfExists('stock_movements');
    }
};
