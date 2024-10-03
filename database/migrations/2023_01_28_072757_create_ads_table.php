<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->cascadeOnDelete();
            $table->foreignId('subcategory_id')->nullable()->constrained('subcategories')->cascadeOnDelete();
            $table->foreignId('country_id')->nullable()->constrained('countries')->cascadeOnDelete();
            $table->foreignId('city_id')->nullable()->constrained('cities')->cascadeOnDelete();
            $table->smallInteger('ad_type')->default(1)->comment('1=>sell,2=>buy,3=>rent,4=>hire');
            $table->smallInteger('featured')->default(0)->comment('0=>normal,1=>featured');
            $table->smallInteger('status')->default(0)->comment('0=>not_complete, 1=>under_review,2=>inactive,3=>active,4=>rejected');
            $table->string('phone')->nullable();
            $table->string('whatsApp')->nullable();
            $table->string('mail')->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->double('price')->default(0.00);
            $table->string('currency')->nullable();
            $table->integer('views')->default(0);
            $table->string('photo')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads');
    }
};