<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('membership_type')->nullable();
            $table->string('membership_number')->nullable();
            $table->decimal('non_member_surcharge', 8, 2)->default(0);
            $table->date('medical_certificate_expires_on')->nullable();
            $table->string('medical_certificate_path')->nullable();
            $table->string('eligibility_status')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tickets', fn (Blueprint $table) => $table->dropColumn([
            'membership_type', 'membership_number', 'non_member_surcharge',
            'medical_certificate_expires_on', 'medical_certificate_path', 'eligibility_status',
        ]));
    }
};
