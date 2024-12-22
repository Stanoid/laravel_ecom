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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId("category_id")->constrained();
            $table->foreignId("brand_id")->constrained();
            $table->text('name');
            $table->text('origin_country');
            $table->integer("discount");
            $table->date("expiration_date");

            $table->text("size");
            $table->longText('description');
            $table->text('img')->default('["https:\/\/via.placeholder.com\/640x480.png\/003300?text=exercitationem","https:\/\/via.placeholder.com\/640x480.png\/0000cc?text=a","https:\/\/via.placeholder.com\/640x480.png\/00bbaa?text=non"]');
            $table->float('price');
            $table->integer('stock');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
