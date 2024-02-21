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
            $table->string('role', 20)->after('id');
            $table->string('name', 100)->change();
            $table->string('surname', 200)->after('name');
            $table->string('nick', 100)->after('surname');
            $table->string('avatar', 255)->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->string('name', 255)->change();
            $table->dropColumn('surname');
            $table->dropColumn('nick');
            $table->dropColumn('avatar');
        });
    }
};
