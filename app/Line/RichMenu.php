<?php

namespace App\Line;


use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\RichMenuBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuAreaBoundsBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuAreaBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuSizeBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;

class RichMenu
{

	private	$accessToken;

	private	$secret;

	private	$bot;
	/**
	 * RichMenu constructor.
	 */
	public function __construct()
	{
		$this->accessToken = getenv('LINE_MAINTENANCE_TOKEN');
		$this->secret      = getenv('LINE_MAINTENANCE_SECRET');
		$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($this->accessToken);
		$this->bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $this->secret]);
	}


	public function create($param)
	{

		dd($this->bot->pushMessage(auth()->user()->lineId, new TextMessageBuilder('Test')));

		$res = $this->bot->createRichMenu(
			new RichMenuBuilder(
				RichMenuSizeBuilder::getFull(),
				true,
				'Nice Rich Menu',
				'Tap to open',
				[
					new RichMenuAreaBuilder(
						new RichMenuAreaBoundsBuilder(0, 10, 1250, 1676),
						new MessageTemplateActionBuilder('message label', 'test message')
					),
					new RichMenuAreaBuilder(
						new RichMenuAreaBoundsBuilder(1250, 0, 1240, 1686),
						new MessageTemplateActionBuilder('message label 2', 'test message 2')
					)
				]
			)
		);
	}

	public function buildMenu()
	{
		$data = RichMenu::find($id);

		switch ($data['size'])
		{
			case 'full':
				$size = RichMenuSizeBuilder::getFull();
				break;
			case 'half':
				$size = RichMenuSizeBuilder::getHalf();
				break;
		}

		$areas = LB_Area::whereIn('id', json_decode($data['area_id']))->with('action', 'bound')->get()->toArray();

		$builtArea = [];

		foreach ($areas as $key => $area)
		{
			$bound           = new RichMenuAreaBoundsBuilder($area['bound']['x'], $area['bound']['y'], $area['bound']['width'], $area['bound']['height']);
			$action          = Line::buildAction($area['action']);
			$builtArea[$key] = new RichMenuAreaBuilder($bound, $action);
		}

		$richMenu = new RichMenuBuilder($size, (bool) $data['selected'], $data['name'], $data['chatBarText'], $builtArea);

		$response = Line::createRichMenu($richMenu);

		$data['richMenuId'] = $response['richMenuId'];
		$data->save();

		return $data['richMenuId'];
	}
}
