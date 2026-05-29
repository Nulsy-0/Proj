<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'trello_id', 'link'])]
class Board extends Model
{
    public function lists()
    {
        return $this->hasMany(ListModel::class, 'board_id');
    }
}
