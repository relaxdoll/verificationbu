<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReplaceTire extends Model
{
	protected $fillable = [
		'date',
		'vehicle_id',
		'fleet_id',

		'mileage',
		'old_tire_id',
		'old_tire_treadDepth',
		'new_tire_id',
		'new_tire_treadDepth',
		'new_tire_total_mileage',
		'new_tire_total_treadDepth',
		'placement',
		'user_id',
		'reason_id',
		'replace_id',
		'status_id',
	];

	protected $relationship = [
		'vehicle',
		'fleet',
		'tire',
		'replace',
		'user',
		'reason',
	];

	protected $whereData = [];

	protected $orderByData = [];

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function vehicle()
	{
		return $this->belongsTo('App\Vehicle');
	}

	public function old()
	{
		return $this->belongsTo('App\Inventory', 'old_tire_id');
	}

	public function new()
	{
		return $this->belongsTo('App\Inventory', 'new_tire_id');
	}

	public function replace()
	{
		return $this->belongsTo('App\ReplaceTire');
	}

	public function reason()
	{
		return $this->belongsTo('App\Reason');
	}

	public function fleet()
	{
		return $this->belongsTo('App\Fleet');
	}

	public function addQuery($queries)
	{
		if (array_key_exists('where', $queries))
		{
			foreach ($queries['where'] as $key => $query)
			{
				array_push($this->whereData, [$key, $query]);
			}
		}

		if (array_key_exists('orderBy', $queries))
		{
			foreach ($queries['orderBy'] as $key => $query)
			{
				array_push($this->orderByData, [$key, $query]);
			}
		}

		return $this;
	}

	public function index()
	{

		return $this->setQueries()->get()->mapWithKeys(function ($item, $key) {

			$inventory = $item->original;

			$relations = $item->relations;

			foreach ($relations as $relationName => $relation)
			{
				$inventory[$relationName] = $relation->name;
			}

			return [$key => ['fleet'      => $item->fleet->name,
							 'fleet_id'   => $item->fleet_id,
							 'id'         => $item->id,
							 'vehicle_id' => $item->vehicle_id,
							 'vehicle'    => $item->vehicle->license,
							 'tire_id'    => $item->tire_id,
							 'tire'       => $item->tire->serial,
							 'replace_id' => $item->replace_id,
							 'replace'    => $item->replace->serial,
							 'reason_id'  => $item->reason_id,
							 'reason'     => $item->reason->reason,
							 'placement'  => $item->placement,
							 'user'       => $item->user->username,
							 'date'       => Carbon::parse($item['date'])->format('d M Y'),
							 'created_at' => Carbon::parse($item['created_at'])->diffForHumans()]];
		});
	}

	public function setQueries()
	{
		$query = $this::query();

		$query->with($this->relationship)->where($this->whereData);

		foreach ($this->orderByData as $orderByDatum)
		{
			$query->orderBy($orderByDatum[0], $orderByDatum[1]);
		}


		return $query;
	}

}
