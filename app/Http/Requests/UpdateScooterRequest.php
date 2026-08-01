<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateScooterRequest extends FormRequest
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
        $scooter = $this->route('scooter');

        return [
            'vehicle_number' => [
                'sometimes',
                'string',
                'max:20',
                Rule::unique('scooters', 'vehicle_number')->ignore($scooter),
            ],

            'serial_number' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('scooters', 'serial_number')->ignore($scooter),
            ],

            'qr_code' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('scooters', 'qr_code')->ignore($scooter),
            ],

            'status' => [
                'sometimes',
                'exists:scooter_statuses,slug',
            ],

            'battery_percentage' => [
                'sometimes',
                'integer',
                'between:0,100',
            ],

            'latitude' => [
                'nullable',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'nullable',
                'numeric',
                'between:-180,180',
            ],

            'last_seen_at' => [
                'nullable',
                'date',
            ],
        ];
    }
}
