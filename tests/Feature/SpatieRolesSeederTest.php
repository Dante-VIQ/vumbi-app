<?php

use Database\Seeders\SpatieRolesSeeder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

it('creates the expected application roles', function () {
    Schema::dropIfExists('roles');
    Schema::create('roles', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('name');
        $table->string('guard_name');
        $table->timestamps();
        $table->unique(['name', 'guard_name']);
    });

    $this->seed(SpatieRolesSeeder::class);

    expect(Role::query()->pluck('name')->all())
        ->toContain('user', 'admin', 'engineer', 'master');
});
