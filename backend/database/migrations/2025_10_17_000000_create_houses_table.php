<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('price');
            $table->unsignedSmallInteger('bedrooms');
            $table->unsignedSmallInteger('bathrooms');
            $table->unsignedSmallInteger('storeys');
            $table->unsignedSmallInteger('garages');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('houses');
    }
};
