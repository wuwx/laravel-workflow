<?php

namespace Wuwx\LaravelWorkflow\tests\Console;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InfoCommandTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->artisan('workflow:list');
        $this->assertTrue(true);
    }
}
