<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'trello_id', 'link', 'stats'])]
class Board extends Model
{
    protected $casts = [
        'stats' => 'array',
    ];

    public function lists()
    {
        return ListModel::query()->where('board_id', $this->id);
    }
}
