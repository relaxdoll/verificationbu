<?php


namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\WHTax as Model;
use Illuminate\Support\Facades\Http;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;


class WHTaxController extends BaseController
{

    protected $name = 'WHTax';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = new Model();

        $response = $model->index()->toArray();

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


        $validator = \Validator::make($input, [
            'value' => 'required',
        ]);


        if ($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());
        }


        $response = Model::create($input);


        return $this->sendResponse($response, $this->name . ' saved successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Model::find($id);


        if (is_null($model))
        {
            return $this->sendError($this->name . ' not found.');
        }

        $model = $model->toArray();

        $model['line_items'] = json_decode($model['line_items']);

        return $this->sendResponse($model, $this->name . ' retrieved successfully.');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Model $model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Model $model)
    {
        $input = $request->all();


//		$validator = Validator::make($input, [
//			'name' => 'required',
//			'detail' => 'required'
//		]);
//
//
//		if($validator->fails()){
//			return $this->sendError('Validation Error.', $validator->errors());
//		}


        $model->name   = $input['name'];
        $model->detail = $input['detail'];
        $model->save();


        return $this->sendResponse($model->toArray(), $this->name . ' updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Model $model
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Model $model)
    {
        $model->delete();


        return $this->sendResponse($model->toArray(), $this->name . ' deleted successfully.');
    }


    public function listBill()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . \Cache::get('access_token')
        ])->get('https://books.zoho.com/api/v3/bills',
            ['organization_id' => 734195937,
             'customview_id'   => '2443874000000177760']);

        $bills = $response->json()['bills'];

        $whtax = [];

        foreach ($bills as $bill)
        {
            if (array_key_exists('cf_has_whtax', $bill))
            {
                if ($bill['cf_has_whtax'] === 'Yes')
                {
                    array_push($whtax, $bill);
                }
            }
        }

        dd($whtax);

        return $this->sendResponse(true, $this->name . ' retrieved successfully.');
    }

    public function getBill($bill_id)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . \Cache::get('access_token')
        ])->get('https://books.zoho.com/api/v3/bills/' . $bill_id,
            ['organization_id' => 734195937]);

        $bill = $response->json()['bill'];

        $details                = [];
        $details['bill_id']     = $bill['bill_id'];
        $details['vendor_id']   = $bill['vendor_id'];
        $details['vendor_name'] = $bill['vendor_name'];
        $details['total']       = $bill['total'];
        $details['date']        = $bill['date'];
        $details['due_date']    = $bill['due_date'];
        $details['bill_number'] = $bill['bill_number'];

        $items = collect($bill['line_items']);

        $group = $items->map(function ($item) {
            return [
                'total'        => $item['item_total'],
                'account_name' => $item['account_name'],
                'description'  => $item['description'],
                'account_id'   => $item['account_id'],
                'quantity'     => $item['quantity'],

                'whtax' => collect($item['item_custom_fields'])->mapWithKeys(function ($item) {
                    return [$item['placeholder'] => $item['value_formatted']];
                })->toArray()];
        })->reject(function ($value, $key) {

            return !array_key_exists('cf_whtax', $value['whtax']);

        })->mapToGroups(function ($item) use ($details) {

            return [array_key_exists('cf_whtax_vendor', $item['whtax']) ? $item['whtax']['cf_whtax_vendor'] : $details['vendor_name'] => $item];

        });

//        dd($group);

        foreach ($group as $key => $vendor)
        {
            $whtax_group = $vendor->mapToGroups(function ($item) {
                $percentage   = substr(substr($item['whtax']['cf_whtax_detail'], strpos($item['whtax']['cf_whtax_detail'], ' ') + 1), 0, - 1);
                $total_wo_vat = array_key_exists('cf_vat', $item['whtax']) ? $item['total'] / 1.07 : $item['total'];

                return [$item['whtax']['cf_whtax'] => [
                    'total'        => $item['total'],
                    'total_wo_vat' => $total_wo_vat,
                    'percentage'   => $percentage,
                    'withhold'     => $total_wo_vat * $percentage / 100,
                    'detail'       => $item['whtax']['cf_whtax_detail'],
                    'raw'          => $item
                ]];
            });

            foreach ($whtax_group as $whtax => $wht_details)
            {
                $detail_group = $wht_details->mapToGroups(function ($item) {

                    return [$item['detail'] => [
                        'total'        => $item['total'],
                        'total_wo_vat' => $item['total_wo_vat'],
                        'withhold'     => $item['withhold'],
                        'percentage'   => $item['percentage'],
                        'raw'          => $item['raw']
                    ]];
                });

                foreach ($detail_group as $detail_name => $detail)
                {
                    $detail_group[$detail_name]['withhold_sum']     = round($detail->pluck('withhold')->sum(), 2);
                    $detail_group[$detail_name]['total_wo_vat_sum'] = round($detail->pluck('total_wo_vat')->sum(), 2);
                }

                $whtax_group[$whtax] = $detail_group;
            }

            $group[$key] = $whtax_group;


        }

        $details['line_items'] = json_encode($group->toArray());

        $model = Model::where('bill_id', $details['bill_id'])->first();

        if (!is_null($model))
        {
            unset($details['bill_id']);

            $response = $model->update($details);
        }
        {
            $response = Model::create($details);
        }


        return $this->sendResponse($response, $this->name . ' retrieved successfully.');
    }

    public function postWHTax(Request $request)
    {

        $bill = json_decode($request->all()['JSONString'])->bill;

        $details                = [];
        $details['line_items']  = [];
        $details['bill_id']     = $bill->bill_id;
        $details['vendor_id']   = $bill->vendor_id;
        $details['vendor_name'] = $bill->vendor_name;
        $details['total']       = $bill->total;
        $details['date']        = $bill->date;
        $details['due_date']    = $bill->due_date;
        $details['bill_number'] = $bill->bill_number;

        foreach ($bill->line_items as $line_item)
        {
            $item                 = [];
            $item['total']        = $line_item->item_total;
            $item['account_name'] = $line_item->account_name;
            $item['description']  = $line_item->description;
            $item['account_id']   = $line_item->account_id;
            $item['quantity']     = $line_item->quantity;
            array_push($details['line_items'], $item);
        }

        $details['line_items'] = json_encode($details['line_items']);

        Model::create($details);

    }

    public function getWHTax()
    {


//        return $this->sendResponse($cache, $this->name . ' retrieved successfully.');
    }

}
