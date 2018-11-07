<?php

namespace Wuwx\LaravelWorkflow\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Admin\Controller;
use Kris\LaravelFormBuilder\Facades\FormBuilder;
use Wuwx\LaravelWorkflow\Entities\Workflow;
use Wuwx\LaravelWorkflow\Entities\Notification;

class NotificationsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Notification::class);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $notifications = Notification::all();
        return view('workflow::admin.notifications.index', compact('notifications'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $form = FormBuilder::create('Wuwx\LaravelWorkflow\Forms\NotificationForm', [
            'name' => sprintf("notifications[%s]", str_random(16)),
        ]);
        return view('workflow::admin.notifications.create', compact('form'));
    }

    public function edit(Notification $notification)
    {
        $form = FormBuilder::create('Wuwx\LaravelWorkflow\Forms\NotificationForm', [
            'method' => 'PUT',
            'model' => $notification,
            'url' => route('workflow.admin.notifications.update', compact('notification')),
        ]);
        return view('workflow::admin.notifications.edit', compact('notification', 'form'));
    }

}
