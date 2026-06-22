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
            $table->enum('role', ['farmer', 'admin'])->default('farmer')->after('password');
            $table->string('phone', 20)->nullable()->after('role');
            $table->text('address')->nullable()->after('phone');
        });

        Schema::create('land_parcels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('parcel_name');
            $table->decimal('area_acres', 8, 2);
            $table->string('district');
            $table->string('state');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('soil_type')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('soil_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parcel_id')->constrained('land_parcels')->cascadeOnDelete();
            $table->date('test_date');
            $table->decimal('ph_level', 4, 2);
            $table->decimal('nitrogen_kg_ha', 8, 2);
            $table->decimal('phosphorus_kg_ha', 8, 2);
            $table->decimal('potassium_kg_ha', 8, 2);
            $table->decimal('organic_carbon_pct', 5, 2)->nullable();
            $table->decimal('zinc_ppm', 6, 2)->nullable();
            $table->decimal('sulfur_ppm', 6, 2)->nullable();
            $table->string('lab_report_path')->nullable();
            $table->timestamps();
        });

        Schema::create('crops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('variety')->nullable();
            $table->enum('season', ['kharif', 'rabi', 'zaid']);
            $table->decimal('rdf_nitrogen', 8, 2);
            $table->decimal('rdf_phosphorus', 8, 2);
            $table->decimal('rdf_potassium', 8, 2);
            $table->integer('duration_days')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('fertilizers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('nitrogen_pct', 5, 2)->default(0);
            $table->decimal('phosphorus_pct', 5, 2)->default(0);
            $table->decimal('potassium_pct', 5, 2)->default(0);
            $table->decimal('price_per_kg', 10, 2);
            $table->string('type')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('fertilizer_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parcel_id')->constrained('land_parcels')->cascadeOnDelete();
            $table->foreignId('soil_test_id')->constrained('soil_tests')->cascadeOnDelete();
            $table->foreignId('crop_id')->constrained('crops')->restrictOnDelete();
            $table->string('season_year', 20);
            $table->decimal('total_cost_inr', 12, 2)->nullable();
            $table->enum('status', ['draft', 'finalized'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('plan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('fertilizer_plans')->cascadeOnDelete();
            $table->foreignId('fertilizer_id')->constrained('fertilizers')->restrictOnDelete();
            $table->decimal('quantity_kg', 10, 2);
            $table->string('application_stage');
            $table->string('application_method')->nullable();
            $table->decimal('cost_inr', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_items');
        Schema::dropIfExists('fertilizer_plans');
        Schema::dropIfExists('fertilizers');
        Schema::dropIfExists('crops');
        Schema::dropIfExists('soil_tests');
        Schema::dropIfExists('land_parcels');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'address']);
        });
    }
};
