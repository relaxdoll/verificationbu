<?php


namespace App\Tool;


use Carbon\Carbon;

class Graph
{

    public static function getGraphByDay($item, $key = null)
    {
        if (!is_null($key))
        {
            $raw = Graph::getDayAxis()->merge($item[$key]->where('created_at', '>', Carbon::now()->subDays(29)->startOfDay()->format('Y-m-d'))->countBy(function ($item) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->toDateString();
            }))->reverse();
        } else
        {
            $raw = Graph::getDayAxis()->merge($item->where('created_at', '>', Carbon::now()->subDays(29)->startOfDay()->format('Y-m-d'))->countBy(function ($item) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->toDateString();
            }))->reverse();
        }

        $x_axis = $raw->keys()->map(function ($item) {
            return (int) substr($item, - 2);
        });

        return ['x_axis' => $x_axis->toArray(), 'y_axis' => array_values($raw->toArray())];
    }

    public static function getGraphByWeek($item, $key = null)
    {
//        return $item->where('created_at', '>', Carbon::now()->subWeeks(1)->startOfWeek()->format('Y-m-d'))->count();
        if (!is_null($key))
        {
            $raw = Graph::getWeekAxis()->merge($item[$key]->where('created_at', '>', Carbon::now()->subWeeks(11)->startOfWeek()->format('Y-m-d'))->countBy(function ($item) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('W-y');
            }))->reverse();
        } else
        {
            $raw = Graph::getWeekAxis()->merge($item->where('created_at', '>', Carbon::now()->subWeeks(11)->startOfWeek()->format('Y-m-d'))->countBy(function ($item) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('W-y');
            }))->reverse();
        }

        $x_axis = self::getWeekAxisInDate();

        return ['x_axis' => $x_axis->toArray(), 'y_axis' => array_values($raw->toArray())];
    }

    public static function getGraphByMonth($item, $key = null)
    {
        if (!is_null($key))
        {
            $raw = Graph::getMonthAxis()->merge($item[$key]->where('created_at', '>', Carbon::now()->subMonths(11)->startOfMonth()->format('Y-m-d'))->countBy(function ($item) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('M');
            }))->reverse();
        } else
        {
            $raw = Graph::getMonthAxis()->merge($item->where('created_at', '>', Carbon::now()->subMonths(11)->startOfMonth()->format('Y-m-d'))->countBy(function ($item) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('M');
            }))->reverse();
        }

        $x_axis = $raw->keys();

        return ['x_axis' => $x_axis->toArray(), 'y_axis' => array_values($raw->toArray())];
    }

    public static function getDayAxis()
    {
        $now                        = Carbon::now();
        $days                       = [];
        $days[$now->toDateString()] = 0;
        for ($i = 1; $i < 30; $i ++)
        {
            $days[$now->subDays(1)->toDateString()] = 0;
        }

        return collect($days);
    }

    public static function getWeekAxis()
    {
        $now                        = Carbon::now();
        $weeks                      = [];
        $weeks[$now->format('W-y')] = 0;
        for ($i = 1; $i < 12; $i ++)
        {
            $weeks[$now->subWeeks(1)->format('W-y')] = 0;
        }

        return collect($weeks);
    }

    public static function getWeekAxisInDate()
    {
        $now                                          = Carbon::now();
        $weeks                                        = [];
        $weeks[$now->format('d M') . ' - ' . 'Today'] = 0;
        for ($i = 1; $i < 12; $i ++)
        {
            $temp                                                                                   = $now->subWeeks(1);
            $weeks[$temp->startOfWeek()->format('d M') . ' - ' . $temp->endOfWeek()->format('d M')] = 0;
        }

        return collect($weeks)->reverse()->keys();
    }

    public static function getMonthAxis()
    {
        $now                       = Carbon::now();
        $months                    = [];
        $months[$now->format('M')] = 0;
        for ($i = 1; $i < 12; $i ++)
        {
            $months[$now->subMonths(1)->format('M')] = 0;
        }

        return collect($months);
    }

    public static function getRefuelRate($data)
    {
        $x = [];
        $y = [];

        foreach ($data as $datum)
        {
            if (!is_null($datum['rate']))
            {
                array_push($x, Carbon::createFromFormat('Y-m-d H:i:s', $datum['created_at'])->format('d M'));

                array_push($y, $datum['rate']);
            }
        }

        $rates      = [];
        $rates['y'] = $y;
        $rates['x'] = $x;

        return $rates;
    }
}
