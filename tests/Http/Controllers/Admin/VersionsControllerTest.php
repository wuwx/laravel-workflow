<?php

namespace Wuwx\LaravelWorkflow\Tests\Http\Controllers\Admin;

use Bouncer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VersionsControllerTest extends TestCase
{
    public function testShouldNotGetIndex()
    {
        $workflow = factory('Wuwx\LaravelWorkflow\Entities\Workflow')->create();

        $response = $this->get("/workflow/admin/workflows/{$workflow->id}/versions");
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testUserShouldNotGetIndex()
    {
        $workflow = factory('Wuwx\LaravelWorkflow\Entities\Workflow')->create();

        $user = factory('App\User')->create();
        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$workflow->id}/versions");
        $response->assertStatus(403);
    }

    public function testAdminShouldGetIndex()
    {
        $workflow = factory('Wuwx\LaravelWorkflow\Entities\Workflow')->create();

        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('browse', 'Wuwx\LaravelWorkflow\Entities\Version');

        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$workflow->id}/versions");
        $response->assertStatus(200);

        $version = factory('Wuwx\LaravelWorkflow\Entities\Version')->create();
        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$version->workflow_id}/versions");
        $response->assertStatus(200);
    }

    public function testAdminShouldGetCreate()
    {
        $workflow = factory('Wuwx\LaravelWorkflow\Entities\Workflow')->create();

        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('add', 'Wuwx\LaravelWorkflow\Entities\Version');

        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$workflow->id}/versions/create");
        $response->assertStatus(200);
    }

    public function testAdminShouldShowVersion()
    {
        $version = factory('Wuwx\LaravelWorkflow\Entities\Version')->create();

        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('read', 'Wuwx\LaravelWorkflow\Entities\Version');

        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$version->workflow_id}/versions/{$version->id}");
        $response->assertStatus(200);
    }

    public function testAdminShouldGetEdit()
    {
        $version = factory('Wuwx\LaravelWorkflow\Entities\Version')->create();

        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('edit', 'Wuwx\LaravelWorkflow\Entities\Version');

        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$version->workflow_id}/versions/{$version->id}/edit");
        $response->assertStatus(200);
    }

    public function testAdminShouldUpdateVersion()
    {
        $version = factory('Wuwx\LaravelWorkflow\Entities\Version')->create();

        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('edit', 'Wuwx\LaravelWorkflow\Entities\Version');

        $response = $this->actingAs($user)->put("/workflow/admin/workflows/{$version->workflow_id}/versions/{$version->id}", []);
        $response->assertStatus(302);
        $response->assertRedirect("/");

        $response = $this->actingAs($user)->put("/workflow/admin/workflows/{$version->workflow_id}/versions/{$version->id}", ['name' => 'v1.1']);
        $response->assertStatus(302);
        $response->assertRedirect("/workflow/admin/workflows/{$version->workflow_id}/versions/{$version->id}");
    }

    public function testAdminShouldStoreVersion()
    {
        $workflow = factory('Wuwx\LaravelWorkflow\Entities\Workflow')->create();

        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('add', 'Wuwx\LaravelWorkflow\Entities\Version');

        $response = $this->actingAs($user)->post("/workflow/admin/workflows/{$workflow->id}/versions", ['name' => 'v1.1']);
        $response->assertStatus(302);
        $response->assertRedirect("/workflow/admin/workflows/{$workflow->id}/versions");
    }

    public function testAdminShouldStoreNewVersion()
    {
        $version = factory('Wuwx\LaravelWorkflow\Entities\Version')->create();
        $process = $version->processes()->create();
        $place = $process->places()->create();
        $process->transitions()->create([
            'froms' => [$place->name],
            'tos'   => [$place->name],
        ]);

        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('add', 'Wuwx\LaravelWorkflow\Entities\Version');

        $response = $this->actingAs($user)->post("/workflow/admin/workflows/{$version->workflow_id}/versions", []);
        $response->assertStatus(302);
        $response->assertRedirect("/");

        $response = $this->actingAs($user)->post("/workflow/admin/workflows/{$version->workflow_id}/versions", ['name' => 'v1.1']);
        $response->assertStatus(302);
        $response->assertRedirect("/workflow/admin/workflows/{$version->workflow_id}/versions");
    }
}
