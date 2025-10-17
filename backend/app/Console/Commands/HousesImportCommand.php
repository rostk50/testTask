<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\House;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

final class HousesImportCommand extends Command
{
    protected $signature = 'houses:import {path=database/seeders/data/houses.csv}';

    protected $description = 'Import houses from CSV';

    public function handle(): int
    {
        $pathArg = (string) $this->argument('path');
        $path = base_path($pathArg);
        if (! is_file($path)) {
            $this->error('File not found');

            return self::FAILURE;
        }

        $h = fopen($path, 'rb');
        if ($h === false) {
            $this->error('Cannot open file');

            return self::FAILURE;
        }

        $header = fgetcsv($h);
        if ($header === false) {
            fclose($h);
            $this->error('Empty CSV');

            return self::FAILURE;
        }

        $header = array_map(
            static fn ($v) => strtolower(trim(str_replace("\xEF\xBB\xBF", '', (string) $v))),
            $header
        );

        $count = 0;

        while (($row = fgetcsv($h)) !== false) {
            if (count($row) !== count($header)) {
                continue;
            }

            $data = array_combine($header, $row);

            $name = trim((string) ($data['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            House::query()->updateOrCreate(
                ['name' => $name],
                [
                    'price' => (int) ($data['price'] ?? 0),
                    'bedrooms' => (int) ($data['bedrooms'] ?? 0),
                    'bathrooms' => (int) ($data['bathrooms'] ?? 0),
                    'storeys' => (int) ($data['storeys'] ?? 0),
                    'garages' => (int) ($data['garages'] ?? 0),
                ]
            );

            $count++;
        }

        fclose($h);
        $this->info((string) $count);
        Log::channel('search')->info('houses_import', [
            'path' => $pathArg,
            'count' => $count,
        ]);

        return self::SUCCESS;
    }
}
