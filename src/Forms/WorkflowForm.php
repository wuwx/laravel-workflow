<?php

namespace Wuwx\LaravelWorkflow\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Entity;

class WorkflowForm extends Form
{
    public function buildForm()
    {
        $this->add('title', 'text', [
            'label' => '名称',
            'rules' => 'required',
        ]);

        $this->add('type', 'choice', [
            'choices' => ['workflow', 'state_machine'],
            'attr' => ['class' => "form-control select2"],
        ]);

        $this->add('marking_store', 'textarea', [
            'value' => json_encode(array_get($this->model, 'marking_store')),
        ]);

        $this->add('initial_place', 'choice', [
            'choices' => array_get($this->model, 'places', collect())->pluck('title', 'name')->toArray(),
            'attr' => ['class' => "form-control select2"],
        ]);

        $this->add('supports', 'textarea', [
            'value' => json_encode(array_get($this->model, 'supports')),
        ]);

        $this->add('submit', 'submit', [
            'label' => '提交',
            'attr' => ['class' => 'btn btn-primary'],
        ]);
    }
}
