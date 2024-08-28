<?php

namespace Singlephon\Hotification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class HotNotification extends Notification
{
    use Queueable;

    protected string $icon;
    protected ?string $title;
    protected ?string $description;
    protected ?string $url;
    protected bool $actionable;

    public function __construct(string $icon, ?string $title, ?string $description, ?string $url, bool $actionable)
    {
        $this->icon = $icon;
        $this->title = $title;
        $this->description = $description;
        $this->url = $url;
        $this->actionable = $actionable;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'icon' => $this->icon,
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
            'actionable' => $this->actionable,
        ];
    }
}
