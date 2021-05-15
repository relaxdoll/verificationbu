<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class api extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'make:api {name} {model}';

	protected $content;


	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$this->content = "<?php


namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\\". $this->argument('model') ." as Model;


class " . $this->argument('name') . "Controller extends BaseController
{

	protected \$name = '".$this->argument('name')."';

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		\$response = Model::all();


		return \$this->sendResponse(\$response->toArray(), \$this->name . 's retrieved successfully.');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request \$request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request \$request)
	{
		\$input = \$request->all();


		\$validator = \Validator::make(\$input, [
			'value' => 'required',
		]);


		if (\$validator->fails())
		{
			return \$this->sendError('Validation Error.', \$validator->errors());
		}


		\$response = Model::create(\$input);


		return \$this->sendResponse(\$response, \$this->name . ' saved successfully.');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int \$id
	 * @return \Illuminate\Http\Response
	 */
	public function show(\$id)
	{
		\$model = Model::find(\$id);


		if (is_null(\$model))
		{
			return \$this->sendError(\$this->name . ' not found.');
		}


		return \$this->sendResponse(\$model->toArray(), \$this->name . ' retrieved successfully.');
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request \$request
	 * @param Model \$model
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request \$request, Model \$model)
	{
		\$input = \$request->all();


//		\$validator = Validator::make(\$input, [
//			'name' => 'required',
//			'detail' => 'required'
//		]);
//
//
//		if(\$validator->fails()){
//			return \$this->sendError('Validation Error.', \$validator->errors());
//		}


		\$model->name   = \$input['name'];
		\$model->detail = \$input['detail'];
		\$model->save();


		return \$this->sendResponse(\$model->toArray(), \$this->name . ' updated successfully.');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Model \$model
	 * @return \Illuminate\Http\Response
	 * @throws \Exception
	 */
	public function destroy(Model \$model)
	{
		\$model->delete();


		return \$this->sendResponse(\$model->toArray(), \$this->name . ' deleted successfully.');
	}

}";

		$response = Storage::disk('api')->put($this->argument('name') . 'Controller.php', $this->content);

		$this->info($this->argument('name') . ' API Controller has been created.');
	}
}
