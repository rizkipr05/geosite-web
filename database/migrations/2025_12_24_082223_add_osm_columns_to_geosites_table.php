<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('geosites', function (Blueprint $table) {
            $table->string('osm_type')->nullable()->after('id'); // node/way/relation
            $table->unsignedBigInteger('osm_id')->nullable()->after('osm_type');
            $table->string('osm_source')->nullable()->after('osm_id'); // optional: "overpass"
            $table->unique(['osm_type','osm_id'], 'geosites_osm_unique');
        });
    }

    public function down(): void
    {
        Schema::table('geosites', function (Blueprint $table) {
            $table->dropUnique('geosites_osm_unique');
            $table->dropColumn(['osm_type','osm_id','osm_source']);
        });
    }
};
