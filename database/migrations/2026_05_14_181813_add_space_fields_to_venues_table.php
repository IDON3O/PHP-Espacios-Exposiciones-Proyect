<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('venues', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('venue_name'); // nullable primero
            $table->string('venue_type')->default('exhibition')->after('slug');
            $table->text('venue_description')->nullable()->after('venue_type');
            $table->text('venue_rules')->nullable()->after('venue_description');
            $table->decimal('price_per_hour', 10, 2)->default(0)->after('venue_rules');
            $table->boolean('is_active')->default(true)->after('price_per_hour');
        });

        // Llenar slugs de registros existentes
        \DB::table('venues')->get()->each(function ($venue) {
            \DB::table('venues')->where('id_venue', $venue->id_venue)->update([
                'slug' => \Illuminate\Support\Str::slug($venue->venue_name) . '-' . $venue->id_venue,
            ]);
        });

        // Ahora sí hacerlo único y no null
        Schema::table('venues', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('venues', function (Blueprint $table) {
            $table->dropColumn(['slug','venue_type','venue_description','venue_rules','price_per_hour','is_active']);
        });
    }
};
