<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up test environment
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}