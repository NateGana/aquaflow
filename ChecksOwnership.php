<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Database\Eloquent\Model;

trait ChecksOwnership
{
    protected function owned(Model $model): Model
    {
        abort_unless($model->user_id === auth()->id(), 403);

        return $model;
    }
}
