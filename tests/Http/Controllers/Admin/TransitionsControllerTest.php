<?php

namespace Wuwx\LaravelWorkflow\Tests\Http\Controllers\Admin;

use Bouncer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransitionsControllerTest extends TestCase
{
    public function testShouldNotGetIndex()
    {
        $transition = factory('Wuwx\LaravelWorkflow\Entities\Transition')->create();
        $workflow = $transition->workflow;

        $response = $this->get("/workflow/admin/workflows/{$workflow->id}/transitions");
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testUserShouldNotGetIndex()
    {
        $transition = factory('Wuwx\LaravelWorkflow\Entities\Transition')->create();
        $workflow = $transition->workflow;

        $user = factory('App\User')->create();
        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$workflow->id}/transitions");
        $response->assertStatus(403);
    }

    public function testAdminShouldGetIndex()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('browse', 'Wuwx\LaravelWorkflow\Entities\Transition');

        $transition = factory('Wuwx\LaravelWorkflow\Entities\Transition')->create();
        $workflow = $transition->workflow;

        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$workflow->id}/transitions");
        $response->assertStatus(200);

        $transition = factory('Wuwx\LaravelWorkflow\Entities\Transition')->create();
        $workflow = $transition->workflow;

        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$workflow->id}/transitions");
        $response->assertStatus(200);
    }

    public function testAdminShouldGetCreate()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('add', 'Wuwx\LaravelWorkflow\Entities\Transition');

        $workflow = factory('Wuwx\LaravelWorkflow\Entities\Workflow')->create();

        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$workflow->id}/transitions/create");
        $response->assertStatus(200);
    }

    public function testAdminShouldGetEdit()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('edit', 'Wuwx\LaravelWorkflow\Entities\Transition');

        $transition = factory('Wuwx\LaravelWorkflow\Entities\Transition')->create();
        $workflow = $transition->workflow;

        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$workflow->id}/transitions/{$transition->id}/edit");
        $response->assertStatus(200);
    }

    public function testAdminShouldDeleteTransition()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('delete', 'Wuwx\LaravelWorkflow\Entities\Transition');

        $transition = factory('Wuwx\LaravelWorkflow\Entities\Transition')->create();
        $workflow = $transition->workflow;

        $response = $this->actingAs($user)->delete("/workflow/admin/workflows/{$workflow->id}/transitions/{$transition->id}");
        $response->assertStatus(302);
        $response->assertRedirect("/workflow/admin/workflows/{$workflow->id}/transitions");
    }

    public function testAdminShouldUpdateTransition()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('edit', 'Wuwx\LaravelWorkflow\Entities\Transition');

        $transition = factory('Wuwx\LaravelWorkflow\Entities\Transition')->create();
        $workflow = $transition->workflow;

        $response = $this->actingAs($user)->put("/workflow/admin/workflows/{$workflow->id}/transitions/{$transition->id}");
        $response->assertStatus(302);
        $response->assertRedirect("/");

        $response = $this->actingAs($user)->put("/workflow/admin/workflows/{$workflow->id}/transitions/{$transition->id}", ['title' => 'transition_1', 'froms' => ['place_1'], 'tos' => ['place_2'], 'role_ids' => []]);
        $response->assertStatus(302);
        $response->assertRedirect("/workflow/admin/workflows/{$workflow->id}/transitions");
    }

    public function testAdminShouldStoreTransition()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('add', 'Wuwx\LaravelWorkflow\Entities\Transition');

        $workflow = factory('Wuwx\LaravelWorkflow\Entities\Workflow')->create();

        $response = $this->actingAs($user)->post("/workflow/admin/workflows/{$workflow->id}/transitions");
        $response->assertStatus(302);
        $response->assertRedirect("/");

        $response = $this->actingAs($user)->post("/workflow/admin/workflows/{$workflow->id}/transitions", ['title' => 'transition_2', 'froms' => ['place_1'], 'tos' => ['place_2'], 'role_ids' => []]);
        $response->assertStatus(302);
        $response->assertRedirect("/workflow/admin/workflows/{$workflow->id}/transitions");
    }
}
