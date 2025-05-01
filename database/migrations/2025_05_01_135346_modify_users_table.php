<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nim')->unique()->after('id');
            $table->foreignId('faculty_id')->nullable()->after('email')->constrained()->onDelete('set null');
            $table->foreignId('location_id')->nullable()->after('faculty_id')->constrained()->onDelete('set null');
            $table->boolean('payment_status')->default(false)->after('location_id');
            $table->boolean('profile_completed')->default(false)->after('payment_status');
            $table->enum('registration_status', ['pending', 'payment_completed', 'location_selected', 'completed'])->default('pending')->after('profile_completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['faculty_id']);
            $table->dropForeign(['location_id']);
            $table->dropColumn(['nim', 'faculty_id', 'location_id', 'payment_status', 'profile_completed', 'registration_status']);
        });
    }
}
