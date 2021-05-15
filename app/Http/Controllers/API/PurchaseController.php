<?php


namespace App\Http\Controllers\API;

use App\Tire;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Purchase;


class PurchaseController extends BaseController
{
    protected $name = 'Purchase';

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = new Purchase();

        $response = $model->addQuery($request->query())->index()->toArray();

        return $this->sendResponse($response, $this->name . ' retrieved successfully.');

    }

    public function indexGroup()
    {
        $model = new Purchase();

        $response = $model->index()->groupBy('purchase')->toArray();

        return $this->sendResponse($response, $this->name . 's retrieved successfully.');
    }

    public function onlyTireIndexGroup()
    {
        $model = new Purchase();

        $response = $model->tireIndex()->groupBy('purchase')->toArray();

        return $this->sendResponse($response, $this->name . 's retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

//
        $validator = \Validator::make($input, [
            'fleet_id'  => 'required',
            'vendor_id' => 'required',
            'brand_id'  => 'required',
            'date'      => 'required',
            'price'     => 'required',
            'amount'    => 'required',
            'user_id'   => 'required'
        ]);
//
//
        if ($validator->fails())
        {
            return $this->sendError('Validation Error . ', $validator->errors());
        }


        $response = Purchase::create($input);


        return $this->sendResponse($response, $this->name . ' saved successfully . ');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function validateAPI(Request $request)
    {
        $input = $request->all();


        $validator = \Validator::make($input, [
            'fleet_id'   => 'required',
            'vendor_id'  => 'required',
            'type_id'    => 'required',
            'treadDepth' => 'required',
            'brand_id'   => 'required',
            'date'       => 'required',
            'price'      => 'required',
            'amount'     => 'required',
            'user_id'    => 'required'
        ]);


        if ($validator->fails())
        {
            return $this->sendError('Validation Error . ', $validator->errors());
        }


//		$product = Vendor::create($input);


        return $this->sendResponse('validated', $this->name . ' saved successfully . ');
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Purchase::find($id);


        if (is_null($data))
        {
            return $this->sendError('Product not found . ');
        }


        return $this->sendResponse($data->toArray(), $this->name . ' retrieved successfully . ');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Purchase $cheque
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $cheque)
    {
        $input = $request->all();


//		$validator = Validator::make($input, [
//			'name' => 'required',
//			'detail' => 'required'
//		]);
//
//
//		if($validator->fails()){
//			return $this->sendError('Validation Error . ', $validator->errors());
//		}


        $cheque->name   = $input['name'];
        $cheque->detail = $input['detail'];
        $cheque->save();


        return $this->sendResponse($cheque->toArray(), $this->name . ' updated successfully . ');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Model $model
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        $response = Purchase::find($id)->delete();

        return $this->sendResponse($response, $this->name . ' deleted successfully.');
    }

    public function getPurchaseByDate(Request $request)
    {
        $response = Purchase::whereBetween('date', [$request->from, $request->to])->where('type_id', '!=', null)->get()->mapWithKeys(function ($item, $key) {
            return [$key => [
                'fleet'      => $item->fleet->name,
                'vendor'     => $item->vendor->name,
                'brand'      => $item->brand->name,
                'brand_id'   => $item->brand_id,
                'id'         => $item->id,
                'vendor_id'  => $item->vendor_id,
                'fleet_id'   => $item->fleet_id,
                'price'      => $item->price,
                'size'       => $item->type->size,
                'type'       => $item->type->name,
                'user'       => $item->user->name,
                'amount'     => $item->amount,
                'purchase'   => $item->purchase_order_number,
                'date'       => Carbon::parse($item['date'])->format('d M Y'),
                'created_at' => Carbon::parse($item['created_at'])->diffForHumans()
            ]];
        })->groupBy('purchase')->toArray();

        return $this->sendResponse($response, $this->name . ' retrieved successfully.');
    }

    public function getTiresByPurchaseId($id)
    {
        $response = Purchase::where('id', $id)->get()->mapWithKeys(function ($item) {
            return $item->tire->map(function ($tire) use ($item) {
                return [
                    'license'       => count($tire->all_placement) === 0 ? null : $tire->all_placement[0]->vehicle->license,
                    'placement'     => count($tire->all_placement) === 0 ? null : $tire->all_placement[0]->placement,
                    'start_date'    => count($tire->all_placement) === 0 ? null : Carbon::parse($tire->all_placement[0]->created_at)->format('d M Y'),
                    'start_mileage' => count($tire->all_placement) === 0 ? null : number_format($tire->all_placement[0]->start_mileage),
                    'fleet'         => count($tire->all_placement) === 0 ? 'Available' : $tire->all_placement[0]->vehicle->fleet->name,
                    'price'         => $tire->price,
                    'serial'        => $tire['serial'],
                    'type'          => $item->type->name,
                    'size'          => $item->type->size,
                    'date'          => Carbon::parse($item['date'])->format('d M Y'),
                    'created_at'    => Carbon::parse($item['created_at'])->diffForHumans()
                ];
            })->groupBy('fleet');
        })->toArray();

        return $this->sendResponse($response, $this->name . ' retrieved successfully.');

    }

    public function getTotalUsedTiresByPurchaseId($purchase_id)
    {
        $response = Purchase::where('id', $purchase_id)->get()->mapWithKeys(function ($item) {
            return $item->tire->map(function ($tire) use ($item) {
                if (count($tire->all_placement) > 0)
                {
                    return [
                        'license'        => $tire->all_placement[0]->vehicle->license,
                        'placement'      => $tire->all_placement[0]->placement,
                        'price'          => $tire->price,
                        'date_for_group' => substr(Carbon::parse($tire->all_placement[0]->created_at)->format('d M Y'), 3)
                    ];
                } else
                {
                    return [
                        'price'          => $tire->price,
                        'date_for_group' => 'Available'
                    ];
                }
            })->groupBy('date_for_group');
        })->toArray();

        $disabledTire = Purchase::where('id', $purchase_id)->get()->mapWithKeys(function ($item) {
            return $item->tire->where('is_available', 0)->where('reason_id', '!=', 6)->whereNotNull('reason_id')->where('is_sold', 0)->map(function ($tire) use ($item) {
                if ($tire->disabled_tire)
                {
                    return [
                        'price'          => $tire->price,
                        'date_for_group' => 'Wating for Sale',
                    ];
                } else
                {
                    return false;
                }
            })->reject(function ($value) {
                return $value === false;
            })->groupBy('date_for_group');
        })->toArray();

        $soldTire = Purchase::where('id', $purchase_id)->get()->mapWithKeys(function ($item) {
            return $item->tire->where('is_available', 0)->where('reason_id', '!=', 6)->whereNotNull('reason_id')->where('is_sold', 1)->map(function ($tire) use ($item) {
                if ($tire->disabled_tire)
                {
                    return [
                        // sold price
                        'price'          => $tire->selling_price,
                        'date_for_group' => 'Sold',
                    ];
                } else
                {
                    return false;
                }
            })->reject(function ($value) {
                return $value === false;
            })->groupBy('date_for_group');
        })->toArray();

        $merged_array1 = array_merge($response, $disabledTire);
        $merged_array2 = array_merge($merged_array1, $soldTire);

        $result = [];

        foreach ($merged_array2 as $key => $item)
        {
            $result[$key]['data']  = $item;
            $result[$key]['count'] = count($item);
            $result[$key]['price'] = array_sum(array_column($item, 'price'));

        }

        return $this->sendResponse($result, $this->name . ' retrieved successfully.');
    }

}
