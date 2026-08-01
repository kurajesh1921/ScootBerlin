<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Public identifier
            $table->uuid('uuid')->unique()->after('id');

            // User role
            $table->foreignId('role_id')
                ->nullable()
                ->after('uuid')
                ->constrained('roles')
                ->restrictOnDelete();

            // Rename Laravel's default name column
            $table->string('name', 150)->change();

            // Contact
            $table->string('phone', 20)->nullable()->after('email');
            $table->timestamp('phone_verified_at')->nullable()->after('phone');

            // Account status
            $table->boolean('is_active')->default(true)->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['role_id']);
            $table->dropColumn([
                'uuid',
                'role_id',
                'phone',
                'phone_verified_at',
                'is_active',
            ]);

            $table->string('name')->change();
        });
    }
};
