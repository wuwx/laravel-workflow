<?php

namespace Wuwx\LaravelWorkflow\Tests\Http\Controllers\Admin;

use Bouncer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationsControllerTest extends TestCase
{
    public function testGuestShouldNotGetIndex()
    {
        $notification = factory('Wuwx\LaravelWorkflow\Entities\Notification')->create();

        $response = $this->get("/workflow/admin/notifications");
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testUserShouldNotGetIndex()
    {
        $notification = factory('Wuwx\LaravelWorkflow\Entities\Notification')->create();

        $user = factory('App\User')->create();
        $response = $this->actingAs($user)->get("/workflow/admin/notifications");
        $response->assertStatus(403);
    }

    public function testAdminShouldGetIndex()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('browse', 'Wuwx\LaravelWorkflow\Entities\Notification');

        $notification = factory('Wuwx\LaravelWorkflow\Entities\Notification')->create();
        $response = $this->actingAs($user)->get("/workflow/admin/notifications");
        $response->assertStatus(200);
    }

    public function testAdminShouldGetEdit()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('edit', 'Wuwx\LaravelWorkflow\Entities\Notification');

        $notification = factory('Wuwx\LaravelWorkflow\Entities\Notification')->create();
        $response = $this->actingAs($user)->get("/workflow/admin/notifications/$notification->id/edit");
        $response->assertStatus(200);
    }

    public function testUserShouldGetCreateWithFormat()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('add', 'Wuwx\LaravelWorkflow\Entities\Notification');

        $response = $this->actingAs($user)->get("/workflow/admin/notifications/create?_format=js");
        $response->assertStatus(200);
    }

    public function testUserShouldGetCreateWithAccept()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('add', 'Wuwx\LaravelWorkflow\Entities\Notification');

        $response = $this->actingAs($user)->get("/workflow/admin/notifications/create", ["Accept" => "application/javascript"]);
        $response->assertStatus(200);
    }
}
