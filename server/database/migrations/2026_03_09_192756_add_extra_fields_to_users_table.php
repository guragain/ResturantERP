<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Names: First, Middle, Last (splitting the original 'name' field)
            if (Schema::hasColumn('users', 'name')) {
                $table->dropColumn('name');
            }
            $table->string('first_name')->after('id');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('last_name')->after('middle_name');
            $table->string('user_name')->unique()->after('last_name'); // Added unique() for usernames

            $table->string('phone')->unique()->nullable()->after('email');
            $table->boolean('is_phone_verified')->default(false)->after('phone');
            $table->timestamp('phone_verified_at')->nullable()->after('is_phone_verified');

            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('password');

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'middle_name',
                'last_name',
                'phone',
                'is_phone_verified',
                'phone_verified_at',
                'status'
            ]);
            $table->dropSoftDeletes();
        });
    }
};
