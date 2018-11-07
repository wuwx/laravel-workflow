<?php

namespace Wuwx\LaravelWorkflow\Tests\Http\Controllers\Admin;

use Bouncer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProcessesControllerTest extends TestCase
{
    public function testShouldNotGetIndex()
    {
        $process = factory('Wuwx\LaravelWorkflow\Entities\Process')->create();
        $version = $process->version;
        $workflow = $version->workflow;

        $response = $this->get("/workflow/admin/workflows/{$workflow->id}/versions/{$version->id}/processes");
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testUserShouldNotGetIndex()
    {
        $process = factory('Wuwx\LaravelWorkflow\Entities\Process')->create();
        $version = $process->version;
        $workflow = $version->workflow;

        $user = factory('App\User')->create();
        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$workflow->id}/versions/{$version->id}/processes");
        $response->assertStatus(403);
    }

    public function testAdminShouldGetIndex()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('browse', 'Wuwx\LaravelWorkflow\Entities\Process');

        $version = factory('Wuwx\LaravelWorkflow\Entities\Version')->create();
        $workflow = $version->workflow;
        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$workflow->id}/versions/{$version->id}/processes");
        $response->assertStatus(200);

        $process = factory('Wuwx\LaravelWorkflow\Entities\Process')->create();
        $version = $process->version;
        $workflow = $version->workflow;
        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$workflow->id}/versions/{$version->id}/processes");
        $response->assertStatus(200);
    }

    public function testAdminShouldShowProcess()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('read', 'Wuwx\LaravelWorkflow\Entities\Process');

        $process = factory('Wuwx\LaravelWorkflow\Entities\Process')->create();
        $version = $process->version;
        $workflow = $version->workflow;
        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$workflow->id}/versions/{$version->id}/processes/{$process->id}");
        $response->assertStatus(200);

        //$place = factory('Wuwx\LaravelWorkflow\Entities\Place')->create();
        //$process = $place->process;
        //$version = $process->version;
        //$workflow = $version->workflow;
        //$response = $this->actingAs($user)->get("/workflow/admin/workflows/{$workflow->id}/versions/{$version->id}/processes/{$process->id}");
        //$response->assertStatus(200);
    }

    public function testAdminShouldGetCreate()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('add', 'Wuwx\LaravelWorkflow\Entities\Process');

        $version = factory('Wuwx\LaravelWorkflow\Entities\Version')->create();
        $workflow = $version->workflow;
        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$workflow->id}/versions/{$version->id}/processes/create");
        $response->assertStatus(200);
    }

    public function testAdminShouldStoreProcess()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('add', 'Wuwx\LaravelWorkflow\Entities\Process');

        $version = factory('Wuwx\LaravelWorkflow\Entities\Version')->create();
        $workflow = $version->workflow;

        $response = $this->actingAs($user)->post("/workflow/admin/workflows/{$workflow->id}/versions/{$version->id}/processes", ['title' => '进程1']);
        $response->assertStatus(302);
        $response->assertRedirect("/");

        $response = $this->actingAs($user)->post("/workflow/admin/workflows/{$workflow->id}/versions/{$version->id}/processes", ['title' => '进程1', 'type' => 'main']);
        $response->assertStatus(302);
        $response->assertRedirect("/workflow/admin/workflows/{$workflow->id}/versions/{$version->id}/processes");
    }

    public function testAdminShouldGetEdit()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('edit', 'Wuwx\LaravelWorkflow\Entities\Process');

        $process = factory('Wuwx\LaravelWorkflow\Entities\Process')->create();
        $version = $process->version;
        $workflow = $version->workflow;
        $response = $this->actingAs($user)->get("/workflow/admin/workflows/{$workflow->id}/versions/{$version->id}/processes/{$process->id}/edit");
        $response->assertStatus(200);
    }

    public function testAdminShouldUpdateProcess()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('edit', 'Wuwx\LaravelWorkflow\Entities\Process');

        $process = factory('Wuwx\LaravelWorkflow\Entities\Process')->create();
        $version = $process->version;
        $workflow = $version->workflow;

        $response = $this->actingAs($user)->put("/workflow/admin/workflows/{$workflow->id}/versions/{$version->id}/processes/{$process->id}", ['title' => 'jjj']);
        $response->assertStatus(302);
        $response->assertRedirect("/");

        $response = $this->actingAs($user)->put("/workflow/admin/workflows/{$workflow->id}/versions/{$version->id}/processes/{$process->id}", ['title' => 'jjj', 'type' => 'main']);
        $response->assertStatus(302);
        $response->assertRedirect("/workflow/admin/workflows/{$workflow->id}/versions/{$version->id}/processes");
    }
}
