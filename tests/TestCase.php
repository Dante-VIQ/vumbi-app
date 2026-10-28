<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // The test environment has no compiled frontend assets (public/build/manifest.json).
        $this->withoutVite();

        // New users are given the "user" role on creation, so the default roles must exist.
        $this->seed(\Database\Seeders\SpatieRolesSeeder::class);
    }
}
