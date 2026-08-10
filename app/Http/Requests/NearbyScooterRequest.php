<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NearbyScooterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
            ],

            'radius' => [
                'nullable',
                'integer',
                'min:100',
                'max:10000',
            ],
        ];
    }

    public function radius(): int
    {
        return (int) $this->input('radius', 1000);
    }

    public function latitude(): float
    {
        return (float) $this->input('latitude');
    }

    public function longitude(): float
    {
        return (float) $this->input('longitude');
    }
}