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
        Schema::create('geosites', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained()->cascadeOnDelete();

        $table->string('name');
        $table->string('slug')->unique();
        $table->text('description');

        $table->decimal('latitude', 10, 7);
        $table->decimal('longitude', 10, 7);

        $table->string('address')->nullable();
        $table->string('region')->nullable();

        $table->string('open_hours')->nullable();
        $table->integer('ticket_price')->nullable();

        $table->enum('status', ['draft','published'])->default('published');
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geosites');
    }
};
