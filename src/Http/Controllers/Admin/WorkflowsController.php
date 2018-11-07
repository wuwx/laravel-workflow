<?php

namespace Wuwx\LaravelWorkflow\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Admin\Controller;
use Kris\LaravelFormBuilder\Facades\FormBuilder;
use Wuwx\LaravelWorkflow\Entities\Workflow;

class WorkflowsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Workflow::class);
    }

    public function index()
    {
        $workflows = Workflow::all();
        return view('workflow::admin.workflows.index', compact('workflows'));
    }

    public function create()
    {
        $form = FormBuilder::create('Wuwx\LaravelWorkflow\Forms\WorkflowForm', [
            'method' => 'POST',
            'url' => route('workflow.admin.workflows.store'),
        ]);
        return view('workflow::admin.workflows.create', compact('form'));
    }

    public function store(Request $request)
    {
        $form = FormBuilder::create('Wuwx\LaravelWorkflow\Forms\WorkflowForm');
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        } else {
            $workflow = Workflow::create($request->all());
            $workflow->supports = json_decode($request->supports, true);
            $workflow->marking_store = json_decode($request->marking_store, true);
            $workflow->save();
            return redirect()->route('workflow.admin.workflows.index');
        }
    }

    public function edit(Workflow $workflow)
    {
        $form = FormBuilder::create('Wuwx\LaravelWorkflow\Forms\WorkflowForm', [
            'method' => 'PUT',
            'url' => route('workflow.admin.workflows.update', $workflow),
            'model' => $workflow,
        ]);
        return view('workflow::admin.workflows.edit', compact('workflow', 'form'));
    }

    public function update(Request $request, Workflow $workflow)
    {
        $form = FormBuilder::create('Wuwx\LaravelWorkflow\Forms\WorkflowForm');
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        } else {
            $workflow->update($request->all());
            $workflow->supports = json_decode($request->supports, true);
            $workflow->marking_store = json_decode($request->marking_store, true);
            $workflow->save();
            return redirect()->route('workflow.admin.workflows.index');
        }
    }

    public function show(Workflow $workflow)
    {
        return view('workflow::admin.workflows.show', compact('workflow'));
    }
}
