<?php

namespace Wuwx\LaravelWorkflow\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Admin\Controller;
use Kris\LaravelFormBuilder\Facades\FormBuilder;

class AttributesController extends Controller
{

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $form = FormBuilder::create('Wuwx\LaravelWorkflow\Forms\AttributeForm', [
            'name' => sprintf("attributes[%s]", str_random(16)),
        ]);
        return view('workflow::admin.attributes.create', compact('form'));
    }
}
