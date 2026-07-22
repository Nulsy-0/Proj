<?php

namespace App\Http\Controllers;

use App\Models\API;
use App\Models\Board;
use App\Models\ListModel;
use App\Models\Utilities;
use Illuminate\Support\Facades\Http;

use function PHPSTORM_META\map;
use function PHPUnit\Framework\isArray;

class ListController extends Controller
{
    public function index(string $id)
    {
        $list = ListModel::findOrFail($id);
        if ($list->start_date == null) {
            toast()->danger("The start date of $list->name is not set! Ask Admin ;)");
            return back();
        }
        if ($list->days == []) {
            toast()->danger("The days of the week of $list->name is not set! Ask Admin ;)");
            return back();
        }

        $cards = API::getCardsCreatedInList($list->trello_id);

        $cardsInfo = [];
        foreach ($cards as $card) {
            $id = $card->data->card->id;
            array_push($cardsInfo, "/cards/$id");
        }

        $cardsInfo = API::APIBatch($cardsInfo);
        $cardsInfo = array_map(fn($item) => $item->{'200'}, $cardsInfo);


        $board = Board::query()->where('id', $list->board_id)->first();
        $trelloBoard = API::getBoard($board->trello_id);



        // Top left vars (Labels Graph)
        $labelsChart = [];
        $labelColors = [];
        $i = 0;

        foreach ($trelloBoard->labelNames as $color => $name) {
            if (!empty($name)) {
                $labelsChart[$name] = 0;
                array_push($labelColors, $i);;
            }
            $i++;
        }

        foreach ($cardsInfo as $card) {
            foreach ($card->labels as $label) {
                $color = $label->color;

                if (!empty($trelloBoard->labelNames->$color)) {
                    $labelName = $trelloBoard->labelNames->$color;
                    $labelsChart[$labelName]++;
                }
            }
        }

        // Top right vars (Distribution Graph)
        $pieData = [];
        $percent = [];

        foreach ($cards as $card) {
            $i = $card->memberCreator->initials;

            $pieData[$i] = ($pieData[$i] ?? 0) + 1;

            $percent[$card->memberCreator->fullName] = [
                'sN' => $i,
                'fN' => $card->memberCreator->fullName,
                'count' => $pieData[$i],
            ];
        }

        $total = count($cards);

        foreach ($percent as &$p) {
            $p['percent'] = round(($p['count'] / $total) * 100, 2);
        }

        $percent = array_values($percent);

        $pieData ?? [
            'None' => 0.0001, // Chart.js will render as 0
        ];

        $percent ?? [
            'None' => [
                'sN' => 'None',
                'fN' => 'None',
                'count' => 0,
                'percent' => 100
            ]
        ];

        // Bottom vars (Statistics)
        $statsRaw = $board->stats;
        $stats = [];
        foreach ($statsRaw as $key => $stat) {
            if (is_array($stat) && isset($stat['fields'])) {
                foreach ($stat['fields'] as $fieldKey => $field) {
                    $type = $stat['type'];
                    $fieldType = $stat['fields'][$fieldKey]['type'];
                    $data = $stat['fields'][$fieldKey]['data'];

                    $stat['fields'][$fieldKey]['data'] = Utilities::statFieldValue($type, $fieldType, $data, $labelsChart, $list);
                }
            }
            $stats[$key] = $stat;
        }

        return view('list.index', compact(
            'list',
            'labelsChart',
            'labelColors',
            'percent',
            'pieData',
            'stats'
        ));
    }
}
