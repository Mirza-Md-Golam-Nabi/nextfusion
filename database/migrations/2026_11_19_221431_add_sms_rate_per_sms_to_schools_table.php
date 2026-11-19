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
        Schema::table('schools', function (Blueprint $table) {
            $table->decimal('sms_rate_per_sms', 8, 4)->nullable()->after('is_active'); // প্রতি SMS-এর চার্জ (৳) — null মানে এখনো ঠিক করা হয়নি
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn('sms_rate_per_sms');
        });
    }
};
