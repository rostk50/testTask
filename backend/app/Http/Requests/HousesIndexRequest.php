<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule as RuleFactory;

final class HousesIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, list<mixed>> */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'min:1'],
            'bedrooms' => ['nullable', 'integer', 'min:0'],
            'bathrooms' => ['nullable', 'integer', 'min:0'],
            'storeys' => ['nullable', 'integer', 'min:0'],
            'garages' => ['nullable', 'integer', 'min:0'],
            'price_from' => ['nullable', 'integer', 'min:0'],
            'price_to' => ['nullable', 'integer', 'min:0', 'gte:price_from'],
            'sort' => ['nullable', RuleFactory::in(['id', 'name', 'price', 'bedrooms', 'bathrooms', 'storeys', 'garages'])],
            'dir' => ['nullable', RuleFactory::in(['asc', 'desc'])],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    /** @return array<string,string> */
    public function messages(): array
    {
        return [
            'name.string' => 'The name must be a string.',
            'name.min' => 'The name must be at least :min characters.',
            'bedrooms.integer' => 'The bedrooms must be an integer.',
            'bedrooms.min' => 'The bedrooms must be at least :min.',
            'bathrooms.integer' => 'The bathrooms must be an integer.',
            'bathrooms.min' => 'The bathrooms must be at least :min.',
            'storeys.integer' => 'The storeys must be an integer.',
            'storeys.min' => 'The storeys must be at least :min.',
            'garages.integer' => 'The garages must be an integer.',
            'garages.min' => 'The garages must be at least :min.',
            'price_from.integer' => 'The price_from must be an integer.',
            'price_from.min' => 'The price_from must be at least :min.',
            'price_to.integer' => 'The price_to must be an integer.',
            'price_to.min' => 'The price_to must be at least :min.',
            'price_to.gte' => 'The price_to must be greater than or equal to price_from.',
            'sort.in' => 'The sort must be one of: id, name, price, bedrooms, bathrooms, storeys, garages.',
            'dir.in' => 'The dir must be either asc or desc.',
            'page.integer' => 'The page must be an integer.',
            'page.min' => 'The page must be at least 1.',
            'per_page.integer' => 'The per_page must be an integer.',
            'per_page.min' => 'The per_page must be at least 1.',
            'per_page.max' => 'The per_page may not be greater than 100.',
        ];
    }
}
