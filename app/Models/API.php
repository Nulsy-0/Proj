<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

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
        $raw = API::APIFetch("lists/$listId/actions/?filter=createCard");
        $pos = 0;
        $filterd = [];
        $mid = '';
        $final = [];

        foreach ($raw as $forPos => $card) {
            if ($forPos != sizeof($raw) - 1 && !property_exists($card->data, 'old') && property_exists($card->data->card,'name')) {
                $filterd[$pos] = $card->data->card;
                $pos++;
            }
        }

        foreach ($filterd as $forPos => $card) {
            $cardUrl = '/cards/' . $card->id;
            $mid .= ($mid == '' ? '' : ',') . $cardUrl;
        }
        $final = API::APIFetchBatch($mid);

        return $final;
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
        $response = Http::get("https://api.trello.com/1/$link", [
            'key' => config('services.trello.key'),
            'token' => config('services.trello.token'),
        ])->throw();

        return json_decode($response->body());
    }

    private function APIFetchBatch(String $urls)
    {
        $response = json_decode(Http::get("https://api.trello.com/1/batch", [
            'urls' => $urls,
            'key' => config('services.trello.key'),
            'token' => config('services.trello.token'),
        ])->throw()->body());

        $formated = [];

        foreach ($response as $key => $value) {
            $formated[$key] = $value->{'200'};
        }

        return $formated;
    }
}
