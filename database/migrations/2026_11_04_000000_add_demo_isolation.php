<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('demo_slug', 64)->default('cro-trail')->index();
            $table->string('race_slug', 64)->nullable();
        });

        Schema::table('tickets', fn (Blueprint $table) => $table->string('demo_slug', 64)->default('cro-trail')->index());
        Schema::table('settings', fn (Blueprint $table) => $table->string('demo_slug', 64)->default('cro-trail')->index());
        Schema::table('discount_codes', fn (Blueprint $table) => $table->string('demo_slug', 64)->default('cro-trail')->index());

        Schema::create('demo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('demo_slug', 64);
            $table->string('key');
            $table->string('value');
            $table->timestamps();
            $table->unique(['demo_slug', 'key']);
        });

        Schema::create('demo_discount_codes', function (Blueprint $table) {
            $table->id();
            $table->string('demo_slug', 64);
            $table->string('code', 100);
            $table->decimal('amount', 8, 2);
            $table->timestamps();
            $table->unique(['demo_slug', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demo_discount_codes');
        Schema::dropIfExists('demo_settings');
        Schema::table('discount_codes', fn (Blueprint $table) => $table->dropColumn('demo_slug'));
        Schema::table('settings', fn (Blueprint $table) => $table->dropColumn('demo_slug'));
        Schema::table('tickets', fn (Blueprint $table) => $table->dropColumn('demo_slug'));
        Schema::table('orders', fn (Blueprint $table) => $table->dropColumn(['demo_slug', 'race_slug']));
    }
};
