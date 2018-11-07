<?php

namespace Wuwx\LaravelWorkflow\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Wechat\Channels\WechatChannel;

class WorkflowNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        //return ['database', WechatChannel::class];
    }

    public function toDatabase($notifiable)
    {
        return [];
    }

    #TODO: 需要继续实现
    public function toWechat($notifiable)
    {
        return [
            'touser' => 'o2s6o0jBF9EFYTvq2zpxDO1jMjfI',
            'template_id' => 'jgiyhu9aCiPFkj3OmHmK4Mppm6OqvKXiv85nLR463Fs',
            'data' => [
                'name' => '测试',
            ],
        ];
    }

}
