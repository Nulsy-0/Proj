<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\Board;
use App\Models\User;

class UserController extends Controller
{
    public function edit(string $id)
    {
        if (User::query()->where("id", $id)->exists()) {
            $user = User::findOrFail($id);
            $boards = Board::get(['id', 'name']);
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
                'boards' => $request->boards ?? [],
            ];

            if ($request->password_reset) {
                $data['password'] = bcrypt($request->password_reset);
            }

            $user->fill($data);

            if ($user->isDirty()) {
                $user->save();
                toast()->success("User updated successfully");
            }

            return back();
        }

        toast()->warning("User doesn't exists");
        return back();
    }

    public function delete(string $id)
    {
        User::destroy($id);
        toast()->success('User deleted successfully');
        return to_route('admin.index');
    }
}
