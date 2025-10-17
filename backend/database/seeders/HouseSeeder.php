<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\House;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class HouseSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/property-data.csv');
        if (! is_file($path)) {
            return;
        }

        $h = fopen($path, 'rb');
        if ($h === false) {
            return;
        }

        $header = fgetcsv($h);
        if ($header === false) {
            fclose($h);

            return;
        }

        $header = array_map(
            static fn ($v) => strtolower(trim(str_replace("\xEF\xBB\xBF", '', (string) $v))),
            $header
        );

        DB::table('houses')->truncate();

        while (($row = fgetcsv($h)) !== false) {
            if (count($row) !== count($header)) {
                continue;
            }

            $data = array_combine($header, $row);

            House::query()->create([
                'name' => trim((string) ($data['name'] ?? '')),
                'price' => (int) ($data['price'] ?? 0),
                'bedrooms' => (int) ($data['bedrooms'] ?? 0),
                'bathrooms' => (int) ($data['bathrooms'] ?? 0),
                'storeys' => (int) ($data['storeys'] ?? 0),
                'garages' => (int) ($data['garages'] ?? 0),
            ]);
        }

        fclose($h);
    }
}
