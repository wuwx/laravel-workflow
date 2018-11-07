<?php

namespace Wuwx\LaravelWorkflow\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Admin\Controller;
use Wuwx\LaravelWorkflow\Entities\Workflow;
use Wuwx\LaravelWorkflow\Entities\Version;
use Wuwx\LaravelWorkflow\Entities\Process;
use Kris\LaravelFormBuilder\Facades\FormBuilder;

class ProcessesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Process::class);
    }

    public function index(Workflow $workflow, Version $version)
    {
        $processes = $version->processes;
        return view('workflow::admin.processes.index', compact('workflow', 'version', 'processes'));
    }

    public function show(Workflow $workflow, Version $version, Process $process)
    {
        return view('workflow::admin.processes.show', compact('workflow', 'version', 'process'));
    }

    public function create(Workflow $workflow, Version $version)
    {
        $form = FormBuilder::create('Wuwx\LaravelWorkflow\Forms\ProcessForm', [
            'method' => 'POST',
            'data' => [
                'version' => $version,
            ],
            'url' => route('workflow.admin.workflows.versions.processes.store', compact('workflow', 'version')),
            'class' => "",
        ]);
        return view('workflow::admin.processes.create', compact('workflow', 'version', 'form'));
    }

    public function store(Request $request, Workflow $workflow, Version $version)
    {
        $form = FormBuilder::create('Wuwx\LaravelWorkflow\Forms\ProcessForm', [
            'data' => [
                'version' => $version,
            ],
        ]);
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        } else {
            $version->processes()->create($request->all());
            return redirect()->route('workflow.admin.workflows.versions.processes.index', [$workflow, $version]);
        }
    }

    public function edit(Workflow $workflow, Version $version, Process $process)
    {
        $form = FormBuilder::create('Wuwx\LaravelWorkflow\Forms\ProcessForm', [
            'method' => 'PUT',
            'model' => $process,
            'url' => route('workflow.admin.workflows.versions.processes.update', [$workflow, $version, $process]),
        ]);
        return view('workflow::admin.processes.edit', compact('workflow', 'version', 'process', 'form'));
    }

    public function update(Request $request, Workflow $workflow, Version $version, Process $process)
    {
        $form = FormBuilder::create('Wuwx\LaravelWorkflow\Forms\ProcessForm');
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        } else {
            $process->update($request->all());
            return redirect()->route('workflow.admin.workflows.versions.processes.index', [$workflow, $version]);
        }
    }

}
