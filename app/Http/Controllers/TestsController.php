<?php

namespace App\Http\Controllers;

use App\Models\API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use function PHPUnit\Framework\containsOnlyObject;

class TestsController extends Controller
{
    public function test(API $API)
    {
        $list1 = '6a1f05013aad2dab40ed8b27'; // CUF Teste
        $list2 = '6a1f05013aad2dab40ed8b29'; // Publicados
        $list3 = '69a7062d1410f54cc3ce9ca8'; // ! CUF Original
        $board1 = '6a1f05013aad2dab40ed8b2d'; // Mediaprisma Teste

        $COL = API::getCardsOnList($list1);
        $COP = API::getCardsCreatedInList($list3);

        dd($COP);

        return view('debug.tests', compact('test'));
    }
}
