<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;
use function PHPUnit\Framework\containsOnlyObject;

class TestsController extends Controller
{
    public function test(Board $board){
        $l = $board->getLists('LBPaal06')[1];
        $list1 = '69f233ffd3db8aa3980dab7b';
        $list2 = '69f234030419280a6da543f8';
        $a = $board->getCardsCreatedInList($list1);
        $b = $board->getBoardId('https://trello.com/b/LBPaal06/teste-para-app');

        dd($b);

        return view('debug.tests', compact('test'));
    }
}
