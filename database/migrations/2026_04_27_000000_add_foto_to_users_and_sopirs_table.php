<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('password');
        });

        Schema::table('sopirs', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('is_online');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('foto');
        });

        Schema::table('sopirs', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }
};
