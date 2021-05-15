<?php


namespace App\Http\Controllers\API;


use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;


class BaseController extends Controller
{
	/**
	 * success response method.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function sendResponse($result, $message = 'success')
	{
		$response = [
			'success' => true,
			'data'    => $result,
			'message' => $message,
		];


		return response()->json($response, 200);
	}


	/**
	 * return error response.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function sendError($error, $errorMessages = [], $code = 404)
	{
		$response = [
			'success' => false,
			'message' => $error,
		];


		if(!empty($errorMessages)){
			$response['data'] = $errorMessages;
		}


		return response()->json($response, $code);
	}

    public function generateRandomString($length = 10)
    {
        $characters       = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString     = '';
        for ($i = 0; $i < $length; $i ++)
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
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
