<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Board;
use App\Models\ListModel;
use App\Models\Utilities;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        foreach ($users as $user) {
            $user->boards = Board::query()->whereIn('id', $user->boards, 'and', false)->pluck('name');
        }

        $boards = Board::orderBy('created_at', 'desc')->get();
        foreach ($boards as $board) {
            $board->lists = ListModel::orderBy('created_at', 'desc')->where('board_id', $board->id)->where('state', 'active')->pluck('name');
        }

        return view('admin.index')->with([
            'users' => $users,
            'boards' => $boards,
        ]);
    }
}
