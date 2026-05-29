<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\Board;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function edit(string $id)
    {
        if (User::query()->where("id", $id)->exists()) {
            $user = User::findOrFail($id);
            $boards = Board::all();
            return view('admin.edit-user', compact('user', 'boards'));
        }
        return back();
    }

    public function update(AuthRequest $request, string $id)
    {

        if (User::query()->where('id', $id)->exists()) {
            $user = User::findOrFail($id);
            $data = [
                'name' => $request->name,
                'state' => $request->state,
                'boards' => $request->boards
            ];

            if ($request->password_reset != null) {
                $data['password'] = bcrypt($request->password_reset);
            }

            $user->update($data);

            return back()->with('success', "User updated successfully");
        }

        return back()->with('warning', "User dosen't exists");
    }
}
