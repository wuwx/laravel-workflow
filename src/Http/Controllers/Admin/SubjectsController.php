<?php

namespace Wuwx\LaravelWorkflow\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Admin\Controller;
use Wuwx\LaravelWorkflow\Entities\Workflow;
use Wuwx\LaravelWorkflow\Entities\Version;
use Wuwx\LaravelWorkflow\Entities\Process;
use Wuwx\LaravelWorkflow\Entities\Subject;

class SubjectsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Subject::class);
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($name, Version $version, Process $process)
    {
        $subjects = $process->subjects()->paginate();
        return view('workflow::admin.subjects.index', compact('workflow', 'version', 'process', 'subjects'));
    }
}
