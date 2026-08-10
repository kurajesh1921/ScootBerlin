<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rides', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('scooter_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('reservation_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->timestamp('started_at');

            $table->timestamp('ended_at')->nullable();

            $table->decimal('start_latitude', 10, 7);

            $table->decimal('start_longitude', 10, 7);

            $table->decimal('end_latitude', 10, 7)->nullable();

            $table->decimal('end_longitude', 10, 7)->nullable();

            $table->unsignedInteger('distance_meters')->default(0);

            $table->unsignedInteger('duration_seconds')->default(0);

            $table->unsignedInteger('cost_cents')->default(0);

            $table->timestamps();

            $table->index('started_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};