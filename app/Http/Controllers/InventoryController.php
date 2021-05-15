<?php

namespace App\Http\Controllers;

use App\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\In;
use JavaScript;

class InventoryController extends Controller
{
    /**
     * DriverController constructor.
     */

    public function __construct()
    {
        $this->getUserDetail();

    }
	/**
	 * Display a listing of the resource.
	 *
	 */
	public function index()
	{
//	    $this->import();
		return view('inventories.index');
//        $data = Inventory::all();
//        foreach ($data as $datum)
//        {
//            dd($datum['id']);
//           Inventory::find($datum['id'])->update(['status_id'=>2]);
//        }
//        dd($data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
	    //
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
        JavaScript::put([
            'id' => $id,
        ]);
        return \View::make('inventories.edit');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}

	public function import()
	{
//		$filePath = Storage::disk('local')->path('inventories.csv');
        $filePath = Storage::disk('local')->path('inven2-20.csv');
//        $filePath = Storage::disk('local')->path('vehicles_view.csv');

//		dd($filePath);
		$delimiter = ',';

		$header = null;
		$data   = array();

		if (($handle = fopen($filePath, 'r')) !== false)
		{
			while (($row = fgetcsv($handle, 0, $delimiter)) !== false)
			{
				if (!$header)
				{
					$headers = $row;

					//Fix ID Bug
					foreach ($headers as $key => $header)
					{
						if (strpos($header, "ID") == 3 && strlen($header) == 5 && $key == 0)
						{
							$headers[$key] = 'ID';
							break;
						}
					}

				} else
				{
					$data[] = array_combine($headers, $row);
				}
			}
			fclose($handle);
		}

//		dd($data);

		foreach ($data as $datum)
		{
		    $check = Inventory::where('serial',$datum['serial'])->first();
////            $check = ReplaceTire::where('new_tire_id',$datum['new_tire_id'])->first();
            if (is_null($check))
            {
//            dd($datum['serial']);
                Inventory::create($datum);
//                ReplaceTire::create($datum);
            }else{
                $check->update($datum);
            }
		}


	}
}
