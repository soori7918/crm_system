<?php

namespace App\Events\ProductChange;

use App\Models\ProductChange;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReturnProductChangeCreated
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
    public function __construct(ProductChange $new_value = null, ProductChange $old_value = null, $user_id = null)
    {
        $this->new_value = $new_value;
        $this->old_value = $old_value;
        $this->model = $new_value ?: $old_value;
        $this->title = $this->model->type == 'return' ? 'سند خروج موقت کالا ایجاد شد' : 'سند قرض';
        $this->description = '';
        $this->user_id = $user_id;
    }


}
