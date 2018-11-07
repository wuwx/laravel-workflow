<?php

namespace Wuwx\LaravelWorkflow\Tests\Http\Controllers\Admin;

use Bouncer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubjectsControllerTest extends TestCase
{
    public function testShouldNotGetIndex()
    {
        $workflow = factory('Wuwx\LaravelWorkflow\Entities\Workflow')->create();

        $response = $this->get("/workflow/admin/workflows/{$workflow->id}/subjects");
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testUserShouldNotGetIndex()
    {
        $workflow = factory('Wuwx\LaravelWorkflow\Entities\Workflow')->create();

        $user = factory('App\User')->create();
        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$workflow->id}/subjects");
        $response->assertStatus(403);
    }

    public function testAdminShouldGetIndex()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('browse', 'Wuwx\LaravelWorkflow\Entities\Subject');

        $workflow = factory('Wuwx\LaravelWorkflow\Entities\Workflow')->create();

        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$workflow->id}/subjects");
        $response->assertStatus(200);
    }
}
