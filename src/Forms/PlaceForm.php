<?php

namespace Wuwx\LaravelWorkflow\Forms;

use Kris\LaravelFormBuilder\Form;
use Silber\Bouncer\Database\Role;

class PlaceForm extends Form
{
    public function buildForm()
    {

        $this->add('title', 'text', [
            'rules' => 'required',
        ]);

        $this->add('description', 'text', [
            'label' => '描述',
        ]);

        $this->add('icon', 'text', [
            'label' => 'icon',
        ]);

        $this->add('color', 'text', [
            'label' => 'color',
        ]);

        $this->add('role_ids', 'choice', [
            'choices' => Role::pluck('title', 'id')->all(),
            'choice_options' => [
                'wrapper' => ['class' => ''],
            ],
            'multiple' => true,
            'attr' => ['class' => "form-control select2"],
        ]);

        $this->add('submit', 'submit', [
            'label' => '提交',
            'attr' => ['class' => 'btn btn-primary'],
        ]);
    }
}
