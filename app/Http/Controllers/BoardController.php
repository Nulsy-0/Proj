<?php

namespace App\Http\Controllers;

use App\Http\Requests\BoardRequest;
use App\Http\Requests\ListRequest;
use App\Models\API;
use App\Models\Board;
use App\Models\ListModel;
use App\Models\Utilities;
use Illuminate\Http\Request;

class BoardController extends Controller
{

    public function create(BoardRequest $request)
    {
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
                'start_date' => null,
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
        ListModel::query()->where('board_id', $id)->update([
            'state' => 'disabled',
        ]);

        ListModel::whereIn('id', $request->lists ?? [], 'and', false)->update([
            'state' => 'active',
        ]);

        $allLists = ListModel::query()->where('board_id', $id)->get()->keyBy('id')->toArray();

        if (isset($request->weeks)) {
            $errors = [];
            foreach ($request->weeks as $key => $listOp) {
                $success = false;
                if (!empty($listOp['start_date']) && !empty($listOp['days'])) {
                    $temp = ucwords(\Carbon\Carbon::parse($listOp['start_date'])->translatedFormat('D'));
                    if (in_array($temp, $listOp["days"])) {
                        ListModel::query()->where('id', $key)->update([
                            "start_date" => $listOp['start_date'],
                            "days" => $listOp['days'],
                        ]);
                        toast()->success($allLists[$key]['name'] . " updated successfully");
                        $success = true;
                    } else {
                        $errors["weeks[{$key}][start_date]"] = "Start date must be on the same week day on {$allLists[$key]['name']}";
                    }
                }

                if (isset($errors["weeks[{$key}][start_date]"])) {
                    toast()->danger($errors["weeks[{$key}][start_date]"]);
                } else if(!$success){
                    toast()->warning($allLists[$key]['name'] . " wasn't fully filled out");
                }
            }
        }

        return back()->withInput()->withErrors($errors ?? []);
    }

    public function delete(Request $request)
    {
        Board::destroy($request->id);
        return to_route('admin.index')->with('success', 'Board deleted successfully');
    }
}
