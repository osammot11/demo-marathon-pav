<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('shuttle_assignment')->nullable();
            $table->timestamp('shuttle_boarded_at')->nullable();
            $table->string('bib_number')->nullable();
            $table->timestamp('bib_collected_at')->nullable();
            $table->unique(['demo_slug', 'bib_number']);
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropUnique(['demo_slug', 'bib_number']);
            $table->dropColumn(['shuttle_assignment', 'shuttle_boarded_at', 'bib_number', 'bib_collected_at']);
        });
    }
};
