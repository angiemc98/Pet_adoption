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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shelter_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('species');
            $table->string('breed')->nullable();
            $table->unsignedTinyInteger('age')->nullable(); // Age in years
            $table->enum('size', ['small', 'medium', 'large'])->nullable(); // Size of the pet
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['available', 'adopted'])->default('available');
            $table->string('photo_url')->nullable(); //ruta en storage/app/public/pets/
            $table->boolean('vaccinated')->default(false);
            $table->boolean('is_sterilized')->default(false);
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
        Schema::dropIfExists('pets');
    }
};
