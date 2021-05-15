<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    protected function getUserDetail(): void
    {
        $this->middleware(function ($request, $next) {

            $user = \Auth::user();

            \JavaScript::put([
                'user'    => $user,
                'miniNav' => \Cache::get('miniNav' . $user->id),
            ]);

            return $next($request);
        });
    }

    /**
     * @param $model
     * @return false|string
     */
    protected function toThaiDate($model)
    {
        $monthTH = [null, 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];

        $date = Carbon::createFromFormat('Y-m-d', $model)->timestamp;

        $thai_date_return = date("j", $date);
        $thai_date_return .= " " . $monthTH[date("n", $date)];
        $thai_date_return .= " " . (date("Y", $date) + 543);

        return $thai_date_return;
    }
}
