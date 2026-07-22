<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

// ! Trello API website -> https://developer.atlassian.com/cloud/trello/rest/

class API extends Model
{

    // Lists ---------------------------------------------------
    protected function getList(String $listId)
    {
        return API::APIFetch("lists/$listId");
    }

    protected function getLists(String $boardId)
    {
        return API::APIFetch("boards/$boardId/lists");
    }

    // Cards ---------------------------------------------------
    protected function getCard(String $cardId)
    {
        return API::APIFetch("cards/$cardId");
    }

    protected function getCardsOnList(String $listId)
    {
        return API::APIFetch("lists/$listId/cards");
    }

    protected function getCardsCreatedInList(String $listId)
    {
        return API::APIAction("lists/$listId/actions", 1000, "createCard");
    }

    // Boards --------------------------------------------------
    protected function getBoardId(String $url)
    {
        $url = str_split(str_replace('https://trello.com/b/', '', $url));
        $i = 0;
        $final = [];
        while ($url[$i] != '/') {
            $final[$i] = $url[$i];
            $i++;
        }

        $boardId = API::getBoard(implode('', $final))->id;

        return $boardId;
    }

    protected function getBoard(String $boardId)
    {
        return API::APIFetch("boards/$boardId");;
    }

    // API -----------------------------------------------------
    private function APIFetch(String $link)
    {
        $response = json_decode(Http::get(
            "https://api.trello.com/1/$link",
            [
                'key' => config('services.trello.key'),
                'token' => config('services.trello.token'),
            ]
        )->throw()->body());

        return $response;
    }

    private function APIAction(String $link, Int $limit, String $action)
    {
        $response = json_decode(Http::get(
            "https://api.trello.com/1/$link",
            [
                'filter' => $action,
                'limit'  => $limit,
                'key'    => config('services.trello.key'),
                'token'  => config('services.trello.token'),
            ]
        )->throw()->body());

        return $response;
    }

    //* API link about batch -> https://developer.atlassian.com/cloud/trello/rest/api-group-batch/#api-batch-get
    protected function APIBatch(array $links)
    {
        $temp = [];
        $final = [];

        foreach ($links as $key => $link) {
            array_push($temp, $link);
            if (count($temp) % 10 == 0 || !isset($links[$key + 1])) {
                $batch = json_decode(Http::get(
                    "https://api.trello.com/1/batch",
                    [
                        'urls' => collect($temp)->implode(','),
                        'key' => config('services.trello.key'),
                        'token' => config('services.trello.token'),
                    ]
                )->throw()->body());
                $final = array_merge($final, $batch);
                $temp = [];
            }
        }

        return $final;
    }
}
