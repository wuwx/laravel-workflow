<?php

namespace Wuwx\LaravelWorkflow\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Admin\Controller;
use Wuwx\LaravelWorkflow\Entities\Workflow;
use Wuwx\LaravelWorkflow\Entities\Version;
use Wuwx\LaravelWorkflow\Entities\Process;
use Wuwx\LaravelWorkflow\Entities\Transition;
use Wuwx\LaravelWorkflow\Entities\Attribute;
use Bouncer;
use Kris\LaravelFormBuilder\Facades\FormBuilder;
use Silber\Bouncer\Database\Role;

class TransitionsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Transition::class);
    }

    public function index(Workflow $workflow)
    {
        $transitions = $workflow->transitions;
        return view('workflow::admin.transitions.index', compact('workflow', 'transitions'));
    }

    public function create(Workflow $workflow)
    {
        $form = FormBuilder::create('Wuwx\LaravelWorkflow\Forms\TransitionForm', [
            'method' => 'POST',
            'url' => route('workflow.admin.workflows.transitions.store', compact('workflow')),
            'data' => [
                'workflow' => $workflow,
            ],
            'class' => "",
        ]);
        return view('workflow::admin.transitions.create', compact('form', 'workflow'));
    }

    public function store(Request $request, Workflow $workflow)
    {
        $form = FormBuilder::create('Wuwx\LaravelWorkflow\Forms\TransitionForm', [
            'data' => [
                'workflow' => $workflow,
            ]
        ]);
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        } else {
            $transition = $workflow->transitions()->create($request->all());
            $transition->roles()->sync($request->role_ids);
            return redirect()->route('workflow.admin.workflows.transitions.index', [$workflow]);
        }
    }

    public function destroy(Workflow $workflow, Transition $transition)
    {
        $transition->delete();
        return redirect()->route('workflow.admin.workflows.transitions.index', [$workflow]);
    }

    public function edit(Workflow $workflow, Transition $transition)
    {
        $form = FormBuilder::create('Wuwx\LaravelWorkflow\Forms\TransitionForm', [
            'method' => 'PUT',
            'model' => $transition,
            'data' => ['workflow' => $workflow],
            'url' => route('workflow.admin.workflows.transitions.update', compact('workflow', 'transition')),
        ]);
        return view('workflow::admin.transitions.edit', compact('workflow', 'transition', 'form'));
    }

    public function update(Request $request, Workflow $workflow, Transition $transition)
    {
        $form = FormBuilder::create('Wuwx\LaravelWorkflow\Forms\TransitionForm', [
            'model' => $transition,
            'data' => ['workflow' => $workflow],
        ]);
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        } else {
            $transition->update($request->all());
            $transition->roles()->sync($request->role_ids);

            foreach($request->get('attributes', []) as $attribute) {
                if ($attribute['id']) {
                    $transition->attributes()->where('id', $attribute['id'])->update($attribute);
                } else {
                    $transition->attributes()->create($attribute);
                }
            }

            foreach($request->get('notifications', []) as $notification) {
                $channels = array_get($notification, "channels");
                array_set($notification, "channels", json_encode($channels));

                if ($notification['id']) {
                    $transition->notifications()->where('id', $notification['id'])->update($notification);
                } else {
                    $transition->notifications()->create($notification);
                }
            }

            return redirect()->route('workflow.admin.workflows.transitions.index', [$workflow]);
        }
    }
}
