<?php

namespace Wuwx\LaravelWorkflow\Tests\Http\Controllers\Admin;

use Bouncer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttributesControllerTest extends TestCase
{
    public function testUserShouldGetCreateWithFormat()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('add', 'Wuwx\LaravelWorkflow\Entities\Attribute');

        $response = $this->actingAs($user)->get("/workflow/admin/attributes/create?_format=js");
        $response->assertStatus(200);
    }

    public function testUserShouldGetCreateWithAccept()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('add', 'Wuwx\LaravelWorkflow\Entities\Attribute');

        $response = $this->actingAs($user)->get("/workflow/admin/attributes/create", ["Accept" => "application/javascript"]);
        $response->assertStatus(200);
    }
}
