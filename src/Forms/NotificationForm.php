<?php

namespace Wuwx\LaravelWorkflow\Forms;

use Kris\LaravelFormBuilder\Form;

class NotificationForm extends Form
{
    public function buildForm()
    {
        $this->add('id', 'hidden');

        $this->add('name', 'text', [
            'label' => "通知类",
        ]);

        $this->add('channels', 'choice', [
            'label' => "通知渠道",
            'choices' => [
                'database' => '站内消息',
                'wechat' => '微信消息',
                'email' => '邮件通知',
            ],
            'multiple' => true,
            'attr' => ['class' => "form-control select2"],
        ]);

        $this->add('notifiables', 'textarea', [
            'label' => "接收对象",
            'attr' => [
                'rows' => "3",
            ],
        ]);

        $this->add('submit', 'submit', [
            'label' => '提交',
            'attr' => ['class' => 'btn btn-primary'],
        ]);
    }
}
