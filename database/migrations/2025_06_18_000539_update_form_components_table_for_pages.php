<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('form_components', function (Blueprint $table) {
            $table->integer('page')->default(1)->after('form_id');
        });
    }

    public function down(): void
    {
        Schema::table('form_components', function (Blueprint $table) {
            $table->dropColumn('page');
        });
    }
};