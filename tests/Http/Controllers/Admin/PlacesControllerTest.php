<?php

namespace Wuwx\LaravelWorkflow\Tests\Http\Controllers\Admin;

use Bouncer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlacesControllerTest extends TestCase
{
    public function testShouldNotGetIndex()
    {
        $place = factory('Wuwx\LaravelWorkflow\Entities\Place')->create();
        $workflow = $place->workflow;

        $response = $this->get("/workflow/admin/workflows/{$workflow->id}/places");
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testUserShouldNotGetIndex()
    {
        $place = factory('Wuwx\LaravelWorkflow\Entities\Place')->create();
        $workflow = $place->workflow;

        $user = factory('App\User')->create();
        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$workflow->id}/places");
        $response->assertStatus(403);
    }

    public function testAdminShouldGetIndex()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('browse', 'Wuwx\LaravelWorkflow\Entities\Place');

        $place = factory('Wuwx\LaravelWorkflow\Entities\Place')->create();
        $workflow = $place->workflow;
        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$workflow->id}/places");
        $response->assertStatus(200);

        $place = factory('Wuwx\LaravelWorkflow\Entities\Place')->create();
        $workflow = $place->workflow;
        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$workflow->id}/places");
        $response->assertStatus(200);
    }

    public function testAdminShouldGetCreate()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('add', 'Wuwx\LaravelWorkflow\Entities\Place');

        $workflow = factory('Wuwx\LaravelWorkflow\Entities\Workflow')->create();
        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$workflow->id}/places/create");
        $response->assertStatus(200);
    }

    public function testAdminShouldGetEdit()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('edit', 'Wuwx\LaravelWorkflow\Entities\Place');

        $place = factory('Wuwx\LaravelWorkflow\Entities\Place')->create();
        $workflow = $place->workflow;
        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$workflow->id}/places/{$place->id}/edit");
        $response->assertStatus(200);
    }

    public function testAdminShouldDeletePlace()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('delete', 'Wuwx\LaravelWorkflow\Entities\Place');

        $place = factory('Wuwx\LaravelWorkflow\Entities\Place')->create();
        $workflow = $place->workflow;
        $response = $this->actingAs($user)->delete("/workflow/admin/workflows/{$workflow->id}/places/{$place->id}");
        $response->assertStatus(302);
        $response->assertRedirect("/workflow/admin/workflows/{$workflow->id}/places");
    }

    public function testAdminShouldUpdatePlace()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('edit', 'Wuwx\LaravelWorkflow\Entities\Place');

        $place = factory('Wuwx\LaravelWorkflow\Entities\Place')->create();
        $workflow = $place->workflow;

        $response = $this->actingAs($user)->put("/workflow/admin/workflows/{$workflow->id}/places/{$place->id}");
        $response->assertStatus(302);
        $response->assertRedirect("/");

        $response = $this->actingAs($user)->put("/workflow/admin/workflows/{$workflow->id}/places/{$place->id}", ['title' => 'place_1']);
        $response->assertStatus(302);
        $response->assertRedirect("/workflow/admin/workflows/{$workflow->id}/places");
    }

    public function testAdminShouldStorePlace()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('add', 'Wuwx\LaravelWorkflow\Entities\Place');

        $workflow = factory('Wuwx\LaravelWorkflow\Entities\Workflow')->create();
        $response = $this->actingAs($user)->post("/workflow/admin/workflows/{$workflow->id}/places");
        $response->assertStatus(302);
        $response->assertRedirect("/");

        $response = $this->actingAs($user)->post("/workflow/admin/workflows/{$workflow->id}/places", ['title' => 'place_2']);
        $response->assertStatus(302);
        $response->assertRedirect("/workflow/admin/workflows/{$workflow->id}/places");
    }
}
