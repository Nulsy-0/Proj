<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Board extends Model
{

    // Lists ---------------------------------------------------
    public function getList(String $listId)
    {
        return Board::APIFetch("lists/$listId");
    }

    public function getLists(String $boardId)
    {
        return Board::APIFetch("boards/$boardId/lists");
    }

    // Cards ---------------------------------------------------
    public function getCard(String $cardId)
    {
        return Board::APIFetch("cards/$cardId");
    }

    public function getCardsOnList(String $listId)
    {
        return Board::APIFetch("lists/$listId/cards");
    }

    public function getCardsCreatedInList(String $listId)
    {
        $raw = Board::APIFetch("lists/$listId/actions/?filter=createCard");
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
        $final = Board::APIFetchBatch($mid);

        return $final;
    }

    // Boards --------------------------------------------------
    public function getBoardId(String $url)
    {
        $url = str_split(str_replace('https://trello.com/b/', '', $url));
        $i = 0;
        $final = [];
        while ($url[$i] != '/') {
            $final[$i] = $url[$i];
            $i++;
        }

        $boardId = Board::getBoard(implode('', $final))->id;

        return $boardId;
    }

    public function getBoard(String $boardId)
    {
        return Board::APIFetch("boards/$boardId");;
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
