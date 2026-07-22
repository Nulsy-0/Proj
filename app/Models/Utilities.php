<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Utilities extends Model
{
    protected function weekDaysSm()
    {
        $dias = [];

        for ($i = 0; $i < 7; $i++) {
            $date = new DateTime();
            $date->modify("Sunday +$i day");
            $dias[] = $date->format('D');
        }

        return $dias;
    }

    public function getView(Request $request)
    {
        $view = $request->view;
        $data = $request->data ?? [];

        return view($view, $data)->render();
    }

    protected function statFieldValue(String $type, String $fieldType, array $data, array $labelsQuant, $list = null)
    {
        switch ($type) {
            case 'labels':
                if ($fieldType == 'value') {
                    return (String) $labelsQuant[array_first($data)];
                } else {
                    return Utilities::statCalculation($type, $data, $labelsQuant);
                }
            case 'dates':
                if ($fieldType == 'value') {
                    return array_first($data);
                } else {
                    return Utilities::statCalculation($type, $data, $labelsQuant, $list);
                }
            case 'extras':
                if(array_first($data) == 'Board Name'){
                    return Board::query()->where('id', $list->board_id)->value('name');
                }
                if(array_first($data) == 'Start Date'){
                    return $list->start_date;
                }
            default:
                return 'Error on statFieldValue()';
        }
    }

    protected function statCalculation(String $type, array $array, array $labelsQuant, $list = null): String
    {
        $calculation = $array[1]; // "+", "-", "×", "÷"

        if ($type === 'labels') {
            return match ($calculation) {
                '+' => (String) ($labelsQuant[$array[0]] + $labelsQuant[$array[2]]),
                '-' => (String) ($labelsQuant[$array[0]] - $labelsQuant[$array[2]]),
                '×' => (String) ($labelsQuant[$array[0]] * $labelsQuant[$array[2]]),
                '÷' => (String) ($labelsQuant[$array[0]] / $labelsQuant[$array[2]]),
                default => 'Invalid calculation operator.'
            };
        }

        if ($type === 'dates') {
            return match ($calculation) {
                '+' => (function () use ($labelsQuant, $array, $list) {
                    if ($labelsQuant != []) {
                        $label = $labelsQuant[$array[2]];
                        $i = 0;
                        $a = 0;

                        $test = '';
                        while ($i < $label) {
                            $temp = date('Y-m-d', strtotime($list->start_date . " +{$a} day"));

                            if (in_array(\Carbon\Carbon::parse($temp)->translatedFormat('D'), $list->days)) {
                                $i++;
                                $test = $temp;
                            }

                            $a++;
                        }
                        return (String) $test;
                    }
                })(),

                '-' => (function () use ($labelsQuant, $array, $list) {
                    if ($labelsQuant != []) {
                        $label = $labelsQuant[$array[2]];
                        $i = 0;
                        $a = 0;

                        $test = '';
                        while ($i < $label) {
                            $temp = date('Y-m-d', strtotime($list->start_date . " -{$a} day"));

                            if (in_array(\Carbon\Carbon::parse($temp)->translatedFormat('D'), $list->days)) {
                                $i++;
                                $test = $temp;
                            }

                            $a++;
                        }
                        return (String) $test;
                    }
                })(),

                default => 'Invalid calculation operator.',
            };
        }

        return 'Invalid type for calculation.';
    }
}
