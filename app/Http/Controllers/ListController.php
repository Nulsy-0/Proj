<?php

namespace App\Http\Controllers;

use App\Models\API;
use App\Models\Board;
use App\Models\ListModel;
use Illuminate\Support\Facades\Http;

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
            toast()->danger("The days of th week of $list->name is not set! Ask Admin ;)");
            return back();
        }

        $trelloList = API::getList($list->trello_id);
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

        foreach ($trelloBoard->labelNames as $color => $name) {
            if (!empty($name)) {
                $labelsChart[$name] = 0;
            }
        }

        $approved = 0;
        $delivered = 0;
        $notDelivered = 0;
        $notApproved = 0;
        foreach ($cardsInfo as $card) {
            foreach ($card->labels as $label) {
                if ($label->name == "Aprovado" || $label->name == "Usado") {
                    $approved++;
                }
                if ($label->name == "Passar para o trello do cliente") {
                    $notDelivered++;
                }
                if ($label->name == "aguarda feedbak cliente") {
                    $delivered++;
                }
                if ($label->name == "Alterar") {
                    $notApproved++;
                }

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

        return view('list.index', compact(
            'list',
            'trelloBoard',
            'labelsChart',
            'percent',
            'pieData',
            'approved',
            'notApproved',
            'delivered',
            'notDelivered'
        ));
    }
}
