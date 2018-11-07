<?php

namespace Wuwx\LaravelWorkflow\tests\Console;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListCommandTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->artisan('workflow:info', ['name' => 'workflow_1']);
        $this->assertTrue(true);
    }
}
