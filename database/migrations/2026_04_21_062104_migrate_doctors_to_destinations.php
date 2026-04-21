<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up()
    {
        // Copy all doctors to destinations
        $doctors = DB::table('doctors')->get();

        foreach ($doctors as $doctor) {
            DB::table('destinations')->insert([
                'name' => $doctor->name,
                'slug' => Str::slug($doctor->name) . '-' . $doctor->id, // Ensure uniqueness
                'location' => $doctor->location ?? null,
                'detail' => $doctor->detail ?? null,
                'media_path' => $doctor->media_path ?? null,
                'legacy_doctor_id' => $doctor->id,
                'created_at' => $doctor->created_at ?? now(),
                'updated_at' => $doctor->updated_at ?? now(),
            ]);
        }
    }

    public function down()
    {
        // No rollback needed; we'll keep destinations
    }
};