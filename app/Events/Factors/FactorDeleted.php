<?php

namespace App\Events\Factors;

use App\Models\Factor;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FactorDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $model;
    public $new_value;
    public $old_value;
    public $title;
    public $description;
    public $user_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Factor $new_value = null, Factor $old_value = null, $user_id = null)
    {
        $this->new_value = $new_value;
        $this->old_value = $old_value;
        $this->model = $new_value ?: $old_value;
        $this->title = $this->model->type == 'input' ? 'فاکتور ورود ایجاد شد' : 'فاکتور خروج  ایجاد شد';
        $this->description = '';
        $this->user_id = $user_id;
    }

}
