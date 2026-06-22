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
        Schema::table('fertilizers', function (Blueprint $table) {
            $table->decimal('unsubsidized_price_per_kg', 10, 2)->default(0)->after('price_per_kg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fertilizers', function (Blueprint $table) {
            $table->dropColumn('unsubsidized_price_per_kg');
        });
    }
};
