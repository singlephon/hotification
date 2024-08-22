<?php

namespace Singlephon\Hotification\Extras;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Singlephon\Hotification\Interfaces\HotificationSenderInterface;

class HotificationSender implements HotificationSenderInterface
{
    protected string $icon = 'default';
    protected ?string $title = null;
    protected ?string $description = null;
    protected ?string $url = null;
    protected bool $actionable = false;
    protected $receivers;

    public function to(Model|Collection|array $receivers): self
    {
        if ($receivers instanceof Model) {
            $receivers = collect([$receivers]);
        } elseif (is_array($receivers)) {
            $receivers = collect($receivers);
        }

        $this->receivers = $receivers;
        return $this;
    }

    public function icon(string $icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    public function title(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function url(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function actionable(bool $actionable = true): self
    {
        $this->actionable = $actionable;
        return $this;
    }

    public function send(): void
    {
        $notificationClass = config('hotification.extras.notification_default');
        $notification = new $notificationClass($this->icon, $this->title, $this->description, $this->url, $this->actionable);

        $this->receivers->each(function ($receiver) use ($notification) {
            $receiver->notify($notification);
        });
    }
}
