<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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

            $table->index('price', 'houses_price_idx');
            $table->index('bedrooms', 'houses_bedrooms_idx');
            $table->index('bathrooms', 'houses_bathrooms_idx');
        });

        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm');
            DB::statement('CREATE INDEX IF NOT EXISTS houses_name_trgm_idx ON houses USING GIN (lower(name) gin_trgm_ops)');
            DB::statement('ALTER TABLE houses ADD CONSTRAINT houses_price_nonneg CHECK (price >= 0)');
            DB::statement('ALTER TABLE houses ADD CONSTRAINT houses_bedrooms_nonneg CHECK (bedrooms >= 0)');
            DB::statement('ALTER TABLE houses ADD CONSTRAINT houses_bathrooms_nonneg CHECK (bathrooms >= 0)');
            DB::statement('ALTER TABLE houses ADD CONSTRAINT houses_storeys_nonneg CHECK (storeys >= 0)');
            DB::statement('ALTER TABLE houses ADD CONSTRAINT houses_garages_nonneg CHECK (garages >= 0)');
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS houses_name_trgm_idx');
            DB::statement('ALTER TABLE houses DROP CONSTRAINT IF EXISTS houses_price_nonneg');
            DB::statement('ALTER TABLE houses DROP CONSTRAINT IF EXISTS houses_bedrooms_nonneg');
            DB::statement('ALTER TABLE houses DROP CONSTRAINT IF EXISTS houses_bathrooms_nonneg');
            DB::statement('ALTER TABLE houses DROP CONSTRAINT IF EXISTS houses_storeys_nonneg');
            DB::statement('ALTER TABLE houses DROP CONSTRAINT IF EXISTS houses_garages_nonneg');
        }

        Schema::table('houses', function (Blueprint $table) {
            $table->dropIndex('houses_price_idx');
            $table->dropIndex('houses_bedrooms_idx');
            $table->dropIndex('houses_bathrooms_idx');
        });

        Schema::dropIfExists('houses');
    }
};
