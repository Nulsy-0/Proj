<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Board;
use App\Models\ListModel;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'asc')->get();
        foreach ($users as $user) {
            $user->boards = Board::query()->whereIn('id', $user->boards, 'and', false)->pluck('name');
        }

        $boards = Board::orderBy('id', 'asc')->get();
        foreach ($boards as $board) {
            $board->lists = ListModel::orderBy('created_at', 'desc')->where('board_id', $board->id)->where('state', 'active')->pluck('name');
        }

        return view('admin.index')->with([
            'users' => $users,
            'boards' => $boards,
        ]);
    }
}
