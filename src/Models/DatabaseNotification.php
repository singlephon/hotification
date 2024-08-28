<?php

namespace Singlephon\Hotification\Models;

use App\Enums\NotificationTypeEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Notifications\DatabaseNotification as BaseNotification;

class DatabaseNotification extends BaseNotification
{
    use Prunable;
    use HasUuids;

    protected $table = 'notifications';

    /**
     * Get the prunable model query.
     */
    public function prunable()
    {
        return static::where('created_at', '<=', now()->subWeek());
    }

    public function resolveRouteBindingQuery($query, $value, $field = null): Relation|Builder
    {
        return parent::resolveRouteBindingQuery($query, $value, $field)
            ->whereMorphedTo('notifiable', auth()->user())
            ->whereIn('type', NotificationTypeEnum::READABLE);
    }
}
