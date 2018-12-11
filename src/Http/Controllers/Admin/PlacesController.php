<?php

namespace Wuwx\LaravelWorkflow\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Admin\Controller;
use Kris\LaravelFormBuilder\Facades\FormBuilder;
use Wuwx\LaravelWorkflow\Entities\Workflow;
use Wuwx\LaravelWorkflow\Entities\Version;
use Wuwx\LaravelWorkflow\Entities\Process;
use Wuwx\LaravelWorkflow\Entities\Place;

class PlacesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Place::class);
    }

    public function index($name)
    {
        $workflow = app('workflow.registry')->get(app()->make("App\\Issue"), $name);
        $places = $workflow->getDefinition()->getPlaces();
        return view('workflow::admin.places.index', compact('workflow', 'places'));
    }

    public function create(Workflow $workflow)
    {
        $form = FormBuilder::create('Wuwx\LaravelWorkflow\Forms\PlaceForm', [
            'method' => 'POST',
            'url' => route('workflow.admin.workflows.places.store', compact('workflow')),
            'class' => "",
        ]);
        return view('workflow::admin.places.create', compact('form', 'workflow'));
    }

    public function store(Request $request, Workflow $workflow)
    {
        $form = FormBuilder::create('Wuwx\LaravelWorkflow\Forms\PlaceForm');
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        } else {
            $workflow->places()->create($request->all());
            return redirect()->route('workflow.admin.workflows.places.index', [$workflow]);
        }
    }

    public function destroy(Workflow $workflow, Place $place)
    {
        $place->delete();
        return redirect()->route('workflow.admin.workflows.places.index', [$workflow]);
    }

    public function edit(Workflow $workflow, Place $place)
    {
        $form = FormBuilder::create('Wuwx\LaravelWorkflow\Forms\PlaceForm', [
            'method' => 'PUT',
            'model' => $place,
            'url' => route('workflow.admin.workflows.places.update', compact('workflow', 'place')),
        ]);
        return view('workflow::admin.places.edit', compact('workflow', 'place', 'form'));
    }

    public function update(Request $request, Workflow $workflow, Place $place)
    {
        $form = FormBuilder::create('Wuwx\LaravelWorkflow\Forms\PlaceForm', [
        ]);
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        } else {
            $place->title       = $request->title;
            $place->description = $request->description;
            $place->icon        = $request->icon;
            $place->color       = $request->color;
            $place->save();

            $place->roles()->sync($request->role_ids);
            $place->processes()->sync($request->process_ids);

            foreach($request->get('attributes', []) as $attribute) {
                if ($attribute['id']) {
                    $place->attributes()->where('id', $attribute['id'])->update($attribute);
                } else {
                    $place->attributes()->create($attribute);
                }
            }

            foreach($request->get('notifications', []) as $notification) {
                if ($notification['id']) {
                    $place->notifications()->where('id', $notification['id'])->update($notification);
                } else {
                    $place->notifications()->create($notification);
                }
            }

            return redirect()->route('workflow.admin.workflows.places.index', [$workflow]);
        }
    }
}
