<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'board_id', 'name', 'trello_id', 'days', 'start_date', 'state'])]

class ListModel extends Model
{
    protected $table = 'lists';

    public function board()
    {
        return $this->belongsTo(Board::class);
    }
    
    protected function casts(): array
    {
        return [
            'start_day'=> 'date',
            'days' => 'array',
        ];
    }
}
