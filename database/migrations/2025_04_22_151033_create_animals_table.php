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
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('species', 100);
            $table->boolean('is_predator');
            $table->timestamp('born_at');
            $table->string('filename')->nullable();
            $table->string('filename_hash')->nullable();
            $table->unsignedBigInteger('enclosure_id');
            $table->foreign('enclosure_id')
                  ->references('id')
                  ->on('enclosures');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
