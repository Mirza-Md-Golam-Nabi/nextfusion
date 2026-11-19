<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * সব স্কুলের SMS vendor (প্রোভাইডার account) — এখানেই লেখা হয়, তারপর প্রতিটা
     * স্কুলে পাঠানো হয়। API key প্রতিটা স্কুলের secret দিয়ে আলাদা করে encrypt হয়ে যায়।
     */
    public function up(): void
    {
        Schema::create('sms_vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('driver'); // bulksmsbd — কোন প্রোভাইডারের API (SmsVendorDriver)
            $table->text('api_key'); // encrypted
            $table->string('sender_id', 20); // যেমন: 8809617613451
            $table->unsignedSmallInteger('priority')->default(1); // ছোট সংখ্যা আগে চেষ্টা হয়
            $table->boolean('is_active')->default(true); // বন্ধ থাকলে স্কুলও এটা দিয়ে SMS পাঠায় না
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_vendors');
    }
};
