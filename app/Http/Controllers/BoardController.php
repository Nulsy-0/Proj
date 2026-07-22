<?php

namespace App\Http\Controllers;

use App\Http\Requests\BoardRequest;
use App\Http\Requests\ListRequest;
use App\Models\API;
use App\Models\Board;
use App\Models\ListModel;
use App\Models\Utilities;

use function Psy\debug;

class BoardController extends Controller
{

    public function create(Board $board, BoardRequest $request)
    {
        $trelloId = API::getBoardId($request->link);
        $boardName = API::getBoard($trelloId)->name;

        $board = Board::create([
            'name' => $boardName,
            'trello_id' => $trelloId,
            'link' => $request->link,
            'stats' => [],
        ]);

        $boardListsRaw = API::getLists($trelloId);
        foreach ($boardListsRaw as $boardList) {
            ListModel::create([
                "board_id" => $board->id,
                "trello_id" => $boardList->id,
                "name" => $boardList->name,
                'start_date' => null,
                'days' => [],
                "state" => "disabled",
            ]);
        }
        toast()->success('Board created successfully');
        toast()->warning('Must configure the Board');
        return to_route('board.edit', $board->id);
    }

    public function edit(String $id)
    {
        if (Board::query()->where("id", $id)->exists()) {
            $board = Board::findOrFail($id);
            $board->lists = ListModel::query()->where('board_id', $id)
                ->orderBy('name', 'asc')
                ->get(['id', 'name', 'state', 'start_date', 'days']);

            $weeks = Utilities::weekDaysSm();

            $trelloBoard = API::getBoard($board->trello_id);

            $labels = [];
            foreach ($trelloBoard->labelNames as $label) {
                if ($label != "") {
                    array_push($labels, $label);
                }
            }

            return view('admin.edit-board', compact('labels', 'board', 'weeks'));
        }
    }

    public function update(ListRequest $request, String $id)
    {
        // * Deactivation and activation of lists
        $board = Board::query()->find($id);

        $currentActive = ListModel::query()->where('board_id', $id)->where('state', 'active')->pluck('id')->sort()->values()->toArray();

        $newActive = collect($request->lists ?? [])->sort()->map(fn($id) => (int) $id)->values()->toArray();

        if ($currentActive !== $newActive) {
            ListModel::query()->where('board_id', $id)->update([
                'state' => 'disabled',
            ]);

            ListModel::query()->whereIn('id', $request->lists ?? [], 'and', false)->update([
                'state' => 'active',
            ]);

            toast()->success('"Activated Lists" updated successfully');
        }

        $allLists = ListModel::query()->where('board_id', $id)->get()->keyBy('id')->toArray();

        // Configurations of lists
        if (isset($request->weeks)) {
            $errors = [];
            foreach ($request->weeks as $key => $listOp) {
                $listDB = ListModel::query()->find($key);
                if ($listDB->state == 'active') {
                    $success = false;
                    if (!empty($listOp['start_date']) && !empty($listOp['days'])) {
                        $temp = ucwords(\Carbon\Carbon::parse($listOp['start_date'])->translatedFormat('D'));
                        if (in_array($temp, $listOp["days"])) {
                            if (
                                $listDB &&
                                (
                                    $listDB->start_date != $listOp['start_date'] ||
                                    $listDB->days != $listOp['days']
                                )
                            ) {
                                ListModel::query()->where('id', $key)->update([
                                    "start_date" => $listOp['start_date'],
                                    "days" => $listOp['days'],
                                ]);
                                toast()->success($allLists[$key]['name'] . " updated successfully");
                            }
                            $success = true;
                        } else {
                            $errors["weeks[{$key}][start_date]"] = "Start date must be on the same week day on {$allLists[$key]['name']}";
                        }
                    }
                }

                if (isset($errors["weeks[{$key}][start_date]"])) {
                    toast()->danger($errors["weeks[{$key}][start_date]"]);
                } else if (isset($success) ? !$success : false) {
                    toast()->warning($allLists[$key]['name'] . " wasn't fully filled out");
                }
            }
        }

        // Stats configuration
        $stats = $request->stats ?? [];
        if ($stats != []) {
            foreach ($request->stats as $key => $stat) {
                $stats[$key]['fields'] = array_values($stat['fields']);
            }
        }
        if ($board->stats != $stats) {
            Board::query()->where('id', $id)->update([
                'stats' => $stats,
            ]);
            toast()->success('The statistics has been updated successfully');
        }

        return back()->withInput()->withErrors($errors ?? []);
    }

    public function refresh(String $id)
    {
        $board = Board::findOrFail($id);

        $trelloLists = API::getLists($board->trello_id);

        $smBoardIds = $board->lists()->pluck('name', 'trello_id')->toArray();

        $smTrelloIds = collect($trelloLists)->mapWithKeys(fn($list) => [
            $list->id => $list->name,
        ])->toArray();

        // lists that wore removed on Trello
        $removed = array_diff($smBoardIds, $smTrelloIds);
        foreach ($removed as $removedId => $name) {
            ListModel::query()->where('trello_id', $removedId)->delete();
        }

        // adicionados (existem no Trello mas não existiam na BD)
        $added = array_diff($smTrelloIds, $smBoardIds);
        foreach ($added as $addedId => $name) {
            ListModel::create([
                "board_id" => $id,
                "trello_id" => $addedId,
                "name" => $name,
                'start_date' => null,
                'days' => [],
                "state" => "disabled",
            ]);
        }

        // mantidos (interseção)
        $kept = array_intersect($smBoardIds, $smTrelloIds);
        foreach($kept as $keptId => $name){
            $list = ListModel::query()->where('trello_id', $keptId);
            if($list->name != $name){
                $list->update([
                    'name' => $name
                ]);
            }
        }

        dd(
            [
                'removed' => $removed,
                'added' => $added,
                'kept' => $kept,
            ],
            [$smBoardIds, $smTrelloIds]
        );
    }

    public function delete(String $id)
    {
        Board::destroy($id);
        toast()->success('Board deleted successfully');
        return to_route('admin.index');
    }
}
