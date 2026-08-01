<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreScooterRequest extends FormRequest
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
            'vehicle_number' => [
                'required',
                'string',
                'max:20',
                'unique:scooters,vehicle_number',
            ],

            'serial_number' => [
                'required',
                'string',
                'max:100',
                'unique:scooters,serial_number',
            ],

            'qr_code' => [
                'required',
                'string',
                'max:100',
                'unique:scooters,qr_code',
            ],

            'status' => [
                'required',
                'exists:scooter_statuses,slug',
            ],
            'battery_percentage' => [
                'nullable',
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
