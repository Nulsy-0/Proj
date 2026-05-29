<?php

namespace App\Http\Controllers;

use App\Http\Requests\BoardRequest;
use App\Http\Requests\ListRequest;
use App\Models\API;
use App\Models\Board;
use App\Models\ListModel;
use App\Models\Utilities;
use DateTime;
use Illuminate\Http\Request;

class BoardController extends Controller
{

    public function create(BoardRequest $request)
    {
        $request->safe()->all();

        $trelloId = API::getBoardId($request->link);
        $boardName = API::getBoard($trelloId)->name;

        $board = Board::create([
            'name' => $boardName,
            'trello_id' => $trelloId,
            'link' => $request->link,
        ]);

        $boardListsRaw = API::getLists($trelloId);
        foreach ($boardListsRaw as $boardList) {
            ListModel::create([
                'board_id' => $board->id,
                "trello_id" => $boardList->id,
                "name" => $boardList->name,
                'start_date' => new DateTime()->format('Y-m-d'),
                'days' => [],
                "state" => "disabled"
            ]);
        }

        return back()->with('success', 'Board created');
    }

    public function edit(string $id)
    {
        if (Board::query()->where("id", $id)->exists()) {
            $board = Board::findOrFail($id);
            $board->lists = ListModel::query()->where('board_id', $id)
                ->orderBy('name', 'asc')
                ->get(['id', 'name', 'state', 'start_date', 'days']);
    
            $weeks = Utilities::weekDaysSm();
            return view('admin.edit-board', compact('board', 'weeks'));
        }
    }

    public function update(ListRequest $request, string $id)
    {
        // dd($request->all());
        $request->safe()->all();

        ListModel::query()->where('board_id', $id)->update([
            'state' => 'disabled',
        ]);

        ListModel::whereIn('id', $request->lists ?? [], 'and', false)->update([
            'state' => 'active',
        ]);

        if ($request->filled('days')) {
            $allLists = ListModel::all()->where('board_id', $id)->keyBy('id')->toArray();

            foreach ($request->days as $key => $listOp) {
                if ($allLists[$key]["start_date"] != $listOp["start_date"] || $allLists[$key]["days"] != isset($listOp['weeks']) ?? []) {
                    ListModel::query()->where('id', $key)->update([
                        "start_date" => $listOp["start_date"],
                        "days" => $listOp['weeks'] ?? [],
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Board updated successfully');
    }

    public function delete(Request $request)
    {
        Board::destroy($request->id);
        return to_route('admin.index')->with('success', 'Board deleted successfuly');
    }
}
