<?php

namespace Wuwx\LaravelWorkflow\Tests\Http\Controllers\Admin;

use Bouncer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkflowsControllerTest extends TestCase
{

    public function testGuestShouldNotGetIndex()
    {
        $response = $this->get("/workflow/admin/workflows");
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testUserShouldNotGetIndex()
    {
        $user = factory('App\User')->create();
        $response = $this->actingAs($user)->get("/workflow/admin/workflows");
        $response->assertStatus(403);
    }

    public function testAdminShouldGetIndex()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('browse', 'Wuwx\LaravelWorkflow\Entities\Workflow');
        $response = $this->actingAs($user)->get("/workflow/admin/workflows");
        $response->assertStatus(200);

        $workflow = factory('Wuwx\LaravelWorkflow\Entities\Workflow')->create();
        $response = $this->actingAs($user)->get("/workflow/admin/workflows");
        $response->assertStatus(200);
    }

    public function testAdminShouldGetCreate()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('add', 'Wuwx\LaravelWorkflow\Entities\Workflow');
        $response = $this->actingAs($user)->get("/workflow/admin/workflows/create");
        $response->assertStatus(200);
    }

    public function testAdminShouldGetEdit()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('edit', 'Wuwx\LaravelWorkflow\Entities\Workflow');

        $workflow = factory('Wuwx\LaravelWorkflow\Entities\Workflow')->create();
        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$workflow->id}/edit");
        $response->assertStatus(200);
    }

    public function testAdminShouldShowWorkflow()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('read', 'Wuwx\LaravelWorkflow\Entities\Workflow');

        $workflow = factory('Wuwx\LaravelWorkflow\Entities\Workflow')->create();
        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$workflow->id}");
        $response->assertStatus(200);
    }

    public function testAdminShouldUpdateWorkflow()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('edit', 'Wuwx\LaravelWorkflow\Entities\Workflow');

        $workflow = factory('Wuwx\LaravelWorkflow\Entities\Workflow')->create();

        $response = $this->actingAs($user)->put("/workflow/admin/workflows/{$workflow->id}", []);
        $response->assertStatus(302);
        $response->assertRedirect('/');

        $response = $this->actingAs($user)->put("/workflow/admin/workflows/{$workflow->id}", ['title' => 'work1']);
        $response->assertStatus(302);
        $response->assertRedirect('/workflow/admin/workflows');
    }

    public function testAdminShouldCreateWorkflow()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('add', 'Wuwx\LaravelWorkflow\Entities\Workflow');

        $response = $this->actingAs($user)->post("/workflow/admin/workflows", []);
        $response->assertStatus(302);
        $response->assertRedirect('/');

        $response = $this->actingAs($user)->post("/workflow/admin/workflows", ['title' => 'work1']);
        $response->assertStatus(302);
        $response->assertRedirect('/workflow/admin/workflows');
    }
}
