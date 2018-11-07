<?php

namespace Wuwx\LaravelWorkflow\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Admin\Controller;
use Wuwx\LaravelWorkflow\Entities\Workflow;
use Wuwx\LaravelWorkflow\Entities\Version;
use Kris\LaravelFormBuilder\Facades\FormBuilder;

class VersionsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Version::class);
    }

    public function index(Workflow $workflow)
    {
        $versions = $workflow->versions;
        return view('workflow::admin.versions.index', compact('workflow', 'versions'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Workflow $workflow)
    {
        $form = FormBuilder::create('Wuwx\LaravelWorkflow\Forms\VersionForm', [
            'method' => 'POST',
            'url' => route('workflow.admin.workflows.versions.store', [$workflow]),
        ]);
        return view('workflow::admin.versions.create', compact('workflow', 'form'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Workflow $workflow, Request $request)
    {
        $form = FormBuilder::create('Wuwx\LaravelWorkflow\Forms\VersionForm');

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();

        } else {
            $version = $workflow->versions()->latest()->first();
            if ($version) {
                DB::beginTransaction();
                $new_version = $version->replicate();
                $new_version->name = $request->input('name');
                $new_version->push();

                foreach($version->processes as $process) {
                    $new_process = $process->replicate();
                    $new_process->version_id = $new_version->id;
                    $new_process->push();

                    foreach($process->places as $place) {
                        $new_place = $place->replicate();
                        $new_place->process_id = $new_process->id;
                        $new_place->push();
                    }

                    foreach($process->transitions as $transition) {
                        $new_transition = $transition->replicate();
                        $new_transition->process_id = $new_process->id;
                        $new_transition->push();
                    }
                }
                DB::commit();
            } else {
                $workflow->versions()->create($request->all());

            }
            return redirect()->route('workflow.admin.workflows.versions.index', [$workflow]);
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(Workflow $workflow, Version $version)
    {
        return view('workflow::admin.versions.show', compact('workflow', 'version'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Workflow $workflow, Version $version)
    {
        $form = FormBuilder::create('Wuwx\LaravelWorkflow\Forms\VersionForm', [
            'method' => 'PUT',
            'model' => $version,
            'url' => route('workflow.admin.workflows.versions.update', [$workflow, $version]),
        ]);
        return view('workflow::admin.versions.edit', compact('workflow', 'version', 'form'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, Workflow $workflow, Version $version)
    {
        $form = FormBuilder::create('Wuwx\LaravelWorkflow\Forms\VersionForm');
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        } else {
            $version->update($request->all());
            return redirect()->route('workflow.admin.workflows.versions.show', [$workflow, $version]);
        }
    }

}
