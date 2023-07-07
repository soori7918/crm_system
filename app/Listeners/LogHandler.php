<?php

namespace App\Listeners;

class LogHandler
{
    public function handle($event)
    {
        $model = $event->model;

        $model->logs()->create([
            'title'=> $event->title,
            'description'=> $event->description,
            'new_value'=> $event->new_value,
            'old_value'=> $event->old_value,
            'created_by' => $event->user_id
        ]);
    }
}
