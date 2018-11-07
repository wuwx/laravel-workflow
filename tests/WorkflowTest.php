<?php

namespace Wuwx\LaravelWorkflow\Tests;

use Bouncer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Wuwx\LaravelWorkflow\Managers\WorkflowManager;
use Wuwx\LaravelWorkflow\Facades\RegistryFacade as Workflow;

class WorkflowTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assign($role->name);
        Bouncer::allow($role->name)->to('apply', 'Wuwx\LaravelWorkflow\Entities\Transition');
        Auth::login($user);
    }

    public function testExample1()
    {
        #TODO: 需要完善
        /*
        $transition = factory('Wuwx\LaravelWorkflow\Entities\Transition')->create();
        $subject = factory('Wuwx\LaravelWorkflow\Entities\Subject')->create([
            'workflow_id' => $transition->workflow_id,
            'entity_id' => 1,
            'entity_type' => 'App\Issue',
            'marking' => array_first($transition->froms),
        ]);

        $manager = new WorkflowManager(app());
        $manager->get($subject, $subject->workflow->name)->apply($subject, $transition->name);
        */

        $this->assertTrue(true);
    }

    public function testExample2()
    {
        $issue = factory('App\Issue')->create();
        $issue->workflow_name = 'issues';
        $issue->save();

        //app('workflow.registry')->get($issue, $issue->workflow_name)->apply($issue, $issue->workflow->transitions()->first()->name);

        $this->assertTrue(true);
    }

    public function testExample3()
    {
        #TODO: 需要完善
        /*
        factory('Wuwx\LaravelWorkflow\Entities\Transition')->create();

        $issue = factory('App\Issue')->create();
        $subject = $issue->subjects()->first();

        Workflow::get($subject, $subject->process->name)->apply($subject, $subject->process->transitions()->first()->name);
        */
        $this->assertTrue(true);
    }
}
