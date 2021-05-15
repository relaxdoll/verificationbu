<?php

namespace App\Http\Controllers;

use App\Console\Commands\location;
use App\Customer;
use App\Driver;
use App\FastTrackBackground;
use App\Fleet;
use App\Line;
use App\LineGroup;
use App\LineLog;
use App\LineUser;
use App\MaintenanceApproval;
use App\Onelink;
use App\ImageTrackReport;
use App\Quota;
use App\Refuel;
use App\Status;
use App\User;
use App\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient as CurlHTTPClientAlias;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder as TextMessageBuilderAlias;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder as ImageMessageBuilderAlias;
use LINE\LINEBot\MessageBuilder\RawMessageBuilder as RawMessageBuilderAlias;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Object_;

class LineController extends Controller
{
    public $bot;

    public function __construct()
    {
        $httpClient = new CurlHTTPClientAlias(getenv('LINE_CHANNEL_TOKEN'));
        $this->bot  = new LINEBot($httpClient, ['channelSecret' => getenv('LINE_CHANNEL_SECRET')]);
    }

    public function userBlocked($data)
    {
        $user = [
            'lineId' => $data->events[0]->source->userId,
            'type'   => $data->events[0]->source->type,
        ];

        $existingUser         = User::where('lineId', $user['lineId'])->first();
        $existingUser->active = 0;

        return $existingUser->save();

    }

    public function receive(Request $request)
    {
        $response = [];

        $data = [
            'log' => json_encode($request->all())
        ];

        $response['log_saved'] = LineLog::create($data);

        $data = json_decode($response['log_saved']->log);

        switch ($data->events[0]->type)
        {
            case 'join':
                $response['event'] = $this->groupJoined($data);
                break;
            case 'follow':
                $response['event'] = $this->userJoined($data);
                break;
//            case 'unfollow':
//                $response['event'] = $this->userBlocked($data);
//                break;
            case 'message':
                $response['event'] = $this->messageReceived($data);
                break;
//            case 'postback':
//                $response['event'] = $this->postbackReceived($data);
//                break;
            default:
                break;
        }


        return $response;
    }

    public function groupJoined($data)
    {

        $group = [
            'lineId' => $data->events[0]->source->groupId,
            'type'   => 'group',
        ];

        Line::create($group);
    }

    public function userJoined($data)
    {
        $user = $this->getUser($data);

        $existingUser = LineUser::where('lineId', $user['lineId'])->first();

        if (!is_null($existingUser))
        {

            return true;
        }

        $profile = json_decode($this->bot->getProfile($user['lineId'])->getRawBody());

        $user['lineName'] = $profile->displayName;

        if (array_key_exists('pictureUrl', $profile))
        {
            $user['avatar'] = $profile->pictureUrl;
        }

        $response['user_created'] = LineUser::create($user);
        $response['user_detail']  = $user;

        return $response;

    }

    public function postbackReceived($data)
    {
        $query = $this->queryToArray($data->events[0]->postback->data);

        switch ($query['action'])
        {
            case 'message':
                return $this->messageDispatcher($data, $query);
            case 'change_menu':
                return $this->menuDispatcher($data, $query['menu'], $query['lan']);

        }
    }

    public function menuDispatcher($data, $menu, $lan)
    {
        switch ($menu)
        {
            case 'dark':
                switch ($lan)
                {
                    case 'en':
                        $this->bot->linkRichMenu($data->events[0]->source->userId, 'richmenu-d25fd395a2fdb3d1af6c74bc3191011a');
                        break;
                    default:
                        $this->bot->linkRichMenu($data->events[0]->source->userId, 'richmenu-9e18668cbea01a4c480d3dd5d1ff6f72');
                        break;
                }
                break;
            default:
                break;
        }
    }

    public function messageDispatcher($data, $query)
    {
        switch ($query['type'])
        {
            case 'flex':
                return $this->flexDispatcher($data, $query['message'], $query['lan']);
                break;
            default:
                break;
        }

    }

    public function flexDispatcher($data, $message, $lan)
    {
        switch ($message)
        {
            case 'intro':
                return $this->bot->replyMessage($data->events[0]->replyToken, $this->flexIntro());

            case 'business-card':
                return $this->bot->replyMessage($data->events[0]->replyToken, $this->flexInformation('business-card'));
            case 'dynamic-qr':
                return $this->bot->replyMessage($data->events[0]->replyToken, $this->flexInformation('dynamic-qr'));
            case 'pdf':
                return $this->bot->replyMessage($data->events[0]->replyToken, $this->flexInformation('pdf'));
            case 'social-media':
                return $this->bot->replyMessage($data->events[0]->replyToken, $this->flexInformation('social-media'));

            case 'contact-card':
                return $this->bot->replyMessage($data->events[0]->replyToken, $this->flexInformation('contact-card'));
            case 'email':
                return $this->bot->replyMessage($data->events[0]->replyToken, $this->flexInformation('email'));
            case 'phone':
                return $this->bot->replyMessage($data->events[0]->replyToken, $this->flexInformation('phone'));
            case 'prompt-pay':
                return $this->bot->replyMessage($data->events[0]->replyToken, $this->flexInformation('prompt-pay'));
            case 'sms':
                return $this->bot->replyMessage($data->events[0]->replyToken, $this->flexInformation('sms'));
            case 'wifi':
                return $this->bot->replyMessage($data->events[0]->replyToken, $this->flexInformation('wifi'));
        }
    }

    public function messageReceived($data)
    {

        if (array_key_exists('text', $data->events[0]->message))
        {

            $message = $data->events[0]->message->text;

            if (substr($message, 0, 22) === 'Group link confirming.')
            {

                $code = substr($message, 35, 100);

                $groupId = $data->events[0]->source->groupId;

                $response = \Cache::put($code, $groupId, now()->addMinutes(10));

                return $this->bot->replyMessage($data->events[0]->replyToken, new TextMessageBuilderAlias("Group confirmed.\r\nReference: " . $code));
            }

            if (substr($message, 0, 7) === 'eeclink')
            {

                $lineId = $data->events[0]->source->groupId;

                $group           = Line::where('lineId', $lineId)->first();
                $group->lineName = substr($message, 8, 100);
                $group->save();

                return $this->bot->replyMessage($data->events[0]->replyToken, new TextMessageBuilderAlias('Group linked successfully'));
            }
        }
    }

    public function pushMessage(Request $request)
    {

//        receive msg
        $message = ($request->message);

//        generate qr
        $rawQrCode = new QrCode($message);
        $name      = rand(10000000000000, 99000000000000);
        $response  = Storage::disk('s3')->put($name . 'jpg', $rawQrCode->writeString(), 'public');

//        push text using line
        $url = Storage::disk('s3')->url($name . 'jpg');

//        $this->pushText('Uac7e1693bd6ce15ab91911e2b1edbb92', $message);
        $this->pushImage('Uac7e1693bd6ce15ab91911e2b1edbb92', $url, $url);
        echo 'Success';
    }

    /**
     * @param $id
     * @param $text
     * @return LINEBot\Response
     */
    public function pushText($id, $text)
    {

        $response = $this->bot->pushMessage($id, new TextMessageBuilderAlias($text));

        return $response;
    }

    public function pushImage($id, $originalUrl, $previewUrl): void
    {

        $response = $this->bot->pushMessage($id, new ImageMessageBuilderAlias($originalUrl, $previewUrl));
    }

    /**
     * @param $data
     * @return array
     */
    public function getUser($data): array
    {
        $user = [
            'lineId'     => $data->events[0]->source->userId,
            'type'       => $data->events[0]->source->type,
            'replyToken' => $data->events[0]->replyToken
        ];

        return $user;
    }

    public function unlinkMenu($id)
    {
        $response = $this->bot->unlinkRichMenu(Driver::find($id)->lineId);

        dd($response);
    }


    private function menuDriver()
    {
//        richmenu-64d5c9193fa51b7fd210300768d9a7d1

        $boundFastTrack = new LINEBot\RichMenuBuilder\RichMenuAreaBoundsBuilder(0, 0, 833, 843);

        $boundRefuel = new LINEBot\RichMenuBuilder\RichMenuAreaBoundsBuilder(834, 0, 833, 843);

        $boundMaintenance = new LINEBot\RichMenuBuilder\RichMenuAreaBoundsBuilder(1668, 0, 832, 843);

//        $actionStatus = new LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder('Change Menu', 'action=change_menu&menu=dark&lan=th');

//        $actionFastTrack = new LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder('intro', 'action=message&type=flex&message=intro&lan=en');

        $actionFastTrack = new LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('FastTrack', 'line://app/1653575237-yDkDbm1r?tab=fasttrack');

        $actionRefuel = new LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Refuel', 'line://app/1653575237-yDkDbm1r?tab=refuel');

        $actionMaintenance = new LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Maintenance', 'line://app/1653575237-yDkDbm1r?tab=tire');


        $areaFastTrack = new LINEBot\RichMenuBuilder\RichMenuAreaBuilder($boundFastTrack, $actionFastTrack);

        $areaRefuel = new LINEBot\RichMenuBuilder\RichMenuAreaBuilder($boundRefuel, $actionRefuel);

        $areaMaintenance = new LINEBot\RichMenuBuilder\RichMenuAreaBuilder($boundMaintenance, $actionMaintenance);

        $areas = [$areaRefuel, $areaFastTrack, $areaMaintenance];

        $richMenu = new LINEBot\RichMenuBuilder(new LINEBot\RichMenuBuilder\RichMenuSizeBuilder(1686, 2500), false, 'driver', 'Driver', $areas);

        $response = $this->bot->createRichMenu($richMenu);

        dd($response);
    }

    public function linkDriverMenu($lineId)
    {
//        $this->bot->linkRichMenu($lineId, 'richmenu-64d5c9193fa51b7fd210300768d9a7d1');
        $this->bot->linkRichMenu($lineId, 'richmenu-f46e742df2546ae6e83c21c48551fc49');
    }

    public function test()
    {
        $driver = Driver::where('fleet_id', '!=', 99)->get()->pluck('lineId')->toArray();

        $exdriver = Driver::where('fleet_id', 99)->get()->pluck('lineId')->toArray();

        $response = $this->bot->bulkLinkRichMenu($driver, 'richmenu-f46e742df2546ae6e83c21c48551fc49');

        $response2 = $this->bot->bulkUnlinkRichMenu($exdriver);

        dd($response);
//        dd($this->buildFastTrack(8762));
        dd($this->linkDriverMenu('U3094f16f5d2775edcaebca950e013091'));


        dd(1);

        dd('stop');

        $refuel = $this->buildRefuel(3456);


        dd($this->bot->pushMessage('U3094f16f5d2775edcaebca950e013091', $refuel['flexBuilder']));

        $this->unlinkMenu(116);
//        $this->linkDriverMenu('U3094f16f5d2775edcaebca950e013091');

//        $drivers = Driver::all();
//        foreach ($drivers as $driver)
//        {
//
//            $this->linkDriverMenu($driver['lineId']);
//        }
//        dd('done');
//        $this->menuDriver();
//        $this->buildFastTrack(1);
//        $flex = $this->buildRefuel(1);


//        $profile = json_decode($this->bot->getProfile('U55a3ef5cf6743e930e37912761a9a563')->getRawBody());
//
//        dd($profile);
//        dd($this->bot->linkRichMenu('Ud7edbeccc179e5cdd0c17d53f5583cc1', 'richmenu-d272af25c7c3770adcae3c4d8b1cc799'));

//        $message = json_decode(LineLog::find(4005)->log);


//        dd($message);
        $model = Driver::all()->pluck('lineId');

        dd($model);

        dd($this->bot->bulkLinkRichMenu($model->toArray(), 'richmenu-b25495208748fcb974b2315725a2db32'));
//        $this->buildFastTrack(24);
//        $this->bot->linkRichMenu('U8a744153ce88abbb7d2152418bd3c545', 'richmenu-d272af25c7c3770adcae3c4d8b1cc799');
//$this->menuDriver();
//        $customer = Customer::find(1);
//        $location = Onelink::where('id', '<=', 5)->get()->toArray();
//
//        $response = $this->buildLocationFlex($location, $customer);
    }

    public function buildRefuel($id)
    {

        $background = FastTrackBackground::getActiveBackground();

        $refuel = Refuel::with('driver', 'vehicle')->where('id', $id)->get()->mapWithKeys(function ($item, $key) {

            $image_array = [];

            $titles = ['เลขไมล์', 'จำนวนลิตร', 'รูปน้ำมันเต็มถัง'];

            foreach (json_decode($item->image_array) as $index => $image)
            {
                $image_array[$index + 1]['path']  = 'https://drive.google.com/uc?export=view&id=' . $image;
                $image_array[$index + 1]['link']  = substr($image, 10);
                $image_array[$index + 1]['title'] = $titles[$index];
            }

            return [$key => ['lineId'      => $item->driver->lineId,
                             'driver'      => $item->driver->name,
                             'avatar'      => $item->driver->avatar,
                             'id'          => $item->id,
                             'odometer'    => '' . number_format($item->odometer),
                             'quantity'    => $item->quantity,
                             'jobId'       => $item->job_id,
                             'vehicle'     => $item->vehicle->license,
                             'rate'        => is_null($item->rate) ? '-' : '' . round($item->rate, 2),
                             'distance'    => is_null($item->distance) ? '-' : '' . number_format($item->distance),
                             'fleet'       => Fleet::find($item->driver->fleet_id)->nameTH,
                             'date'        => Carbon::createFromTimestamp(time())->format('j M Y'),
                             'time'        => Carbon::createFromTimestamp(time())->format('H:i'),
                             'image_array' => $image_array,
            ]];
        });

        $refuel = $refuel->toArray()[0];

//        dd($refuel);

        $bubbles = [];

        array_push($bubbles,
            [
                'type' => 'bubble',
                'body' =>
                    array(
                        'type'       => 'box',
                        'layout'     => 'vertical',
                        'contents'   =>
                            array(
                                0 =>
                                    array(
                                        'type'        => 'image',
                                        'url'         => $background,
                                        'size'        => 'full',
                                        'aspectMode'  => 'cover',
                                        'aspectRatio' => '1:1',
                                        'gravity'     => 'top',
                                    ),
                                1 =>
                                    array(
                                        'type'            => 'box',
                                        'layout'          => 'vertical',
                                        'contents'        =>
                                            array(
                                                0 =>
                                                    array(
                                                        'type' => 'text',
                                                        'text' => ' ',
                                                    ),
                                            ),
                                        'position'        => 'absolute',
                                        'width'           => '100%',
                                        'height'          => '100%',
                                        'backgroundColor' => '#00000060',
                                    ),
                                2 =>
                                    array(
                                        'type'            => 'box',
                                        'layout'          => 'vertical',
                                        'contents'        =>
                                            array(
                                                0 =>
                                                    array(
                                                        'type'     => 'box',
                                                        'layout'   => 'vertical',
                                                        'contents' =>
                                                            array(
                                                                0 =>
                                                                    array(
                                                                        'type'   => 'text',
                                                                        'text'   => $refuel['rate'] . ' km/l',
                                                                        'size'   => 'lg',
                                                                        'color'  => '#ffffff',
                                                                        'weight' => 'regular',
                                                                        'align'  => 'center',
                                                                    ),
                                                            ),
                                                    ),
                                                1 =>
                                                    array(
                                                        'type'         => 'box',
                                                        'layout'       => 'vertical',
                                                        'contents'     =>
                                                            array(
                                                                0 =>
                                                                    array(
                                                                        'type'   => 'text',
                                                                        'text'   => 'Name',
                                                                        'size'   => 'xxs',
                                                                        'color'  => '#aeaebd',
                                                                        'weight' => 'regular',
                                                                        'align'  => 'start',
                                                                    ),
                                                                1 =>
                                                                    array(
                                                                        'type'  => 'text',
                                                                        'text'  => $refuel['driver'],
                                                                        'color' => '#ffffff',
                                                                        'size'  => 'sm',
                                                                    ),
                                                            ),
                                                        'offsetTop'    => '0px',
                                                        'paddingStart' => '35px',
                                                    ),
                                                2 =>
                                                    array(
                                                        'type'         => 'box',
                                                        'layout'       => 'horizontal',
                                                        'contents'     =>
                                                            array(
                                                                0 =>
                                                                    array(
                                                                        'type'     => 'box',
                                                                        'layout'   => 'vertical',
                                                                        'contents' =>
                                                                            array(
                                                                                0 =>
                                                                                    array(
                                                                                        'type'   => 'text',
                                                                                        'size'   => 'xxs',
                                                                                        'color'  => '#aeaebd',
                                                                                        'weight' => 'regular',
                                                                                        'align'  => 'start',
                                                                                        'text'   => 'License Plate',
                                                                                    ),
                                                                                1 =>
                                                                                    array(
                                                                                        'type'  => 'text',
                                                                                        'text'  => $refuel['vehicle'],
                                                                                        'color' => '#ffffff',
                                                                                        'size'  => 'sm',
                                                                                    ),
                                                                            ),
                                                                        'flex'     => 1,
                                                                        'width'    => '50%',
                                                                    ),
                                                                1 =>
                                                                    array(
                                                                        'type'     => 'box',
                                                                        'layout'   => 'vertical',
                                                                        'contents' =>
                                                                            array(
                                                                                0 =>
                                                                                    array(
                                                                                        'type'   => 'text',
                                                                                        'text'   => 'Fuel (litre)',
                                                                                        'size'   => 'xxs',
                                                                                        'color'  => '#aeaebd',
                                                                                        'weight' => 'regular',
                                                                                        'align'  => 'start',
                                                                                    ),
                                                                                1 =>
                                                                                    array(
                                                                                        'type'  => 'text',
                                                                                        'text'  => $refuel['quantity'],
                                                                                        'color' => '#ffffff',
                                                                                        'size'  => 'sm',
                                                                                    ),
                                                                            ),
                                                                        'flex'     => 1,
                                                                        'position' => 'relative',
                                                                        'width'    => '50%',
                                                                    ),
                                                            ),
                                                        'offsetTop'    => '5px',
                                                        'width'        => '100%',
                                                        'paddingStart' => '35px',
                                                    ),
                                                3 =>
                                                    array(
                                                        'type'         => 'box',
                                                        'layout'       => 'horizontal',
                                                        'contents'     =>
                                                            array(
                                                                0 =>
                                                                    array(
                                                                        'type'     => 'box',
                                                                        'layout'   => 'vertical',
                                                                        'contents' =>
                                                                            array(
                                                                                0 =>
                                                                                    array(
                                                                                        'type'   => 'text',
                                                                                        'text'   => 'Odometer',
                                                                                        'size'   => 'xxs',
                                                                                        'color'  => '#aeaebd',
                                                                                        'weight' => 'regular',
                                                                                        'align'  => 'start',
                                                                                    ),
                                                                                1 =>
                                                                                    array(
                                                                                        'type'  => 'text',
                                                                                        'text'  => $refuel['odometer'],
                                                                                        'color' => '#ffffff',
                                                                                        'size'  => 'sm',
                                                                                    ),
                                                                            ),
                                                                        'flex'     => 1,
                                                                        'width'    => '50%',
                                                                    ),
                                                                1 =>
                                                                    array(
                                                                        'type'     => 'box',
                                                                        'layout'   => 'vertical',
                                                                        'contents' =>
                                                                            array(
                                                                                0 =>
                                                                                    array(
                                                                                        'type'   => 'text',
                                                                                        'text'   => 'Distance (km)',
                                                                                        'size'   => 'xxs',
                                                                                        'color'  => '#aeaebd',
                                                                                        'weight' => 'regular',
                                                                                        'align'  => 'start',
                                                                                    ),
                                                                                1 =>
                                                                                    array(
                                                                                        'type'  => 'text',
                                                                                        'text'  => $refuel['distance'],
                                                                                        'color' => '#ffffff',
                                                                                        'size'  => 'sm',
                                                                                    ),
                                                                            ),
                                                                        'flex'     => 1,
                                                                        'position' => 'relative',
                                                                        'width'    => '50%',
                                                                    ),
                                                            ),
                                                        'offsetTop'    => '10px',
                                                        'paddingStart' => '35px',
                                                    ),
                                            ),
                                        'position'        => 'absolute',
                                        'offsetBottom'    => '0px',
                                        'offsetStart'     => '0px',
                                        'offsetEnd'       => '0px',
                                        'paddingTop'      => '57px',
                                        'offsetTop'       => '75px',
                                        'backgroundColor' => '#22222280',
                                    ),
                                3 =>
                                    array(
                                        'type'      => 'box',
                                        'layout'    => 'horizontal',
                                        'contents'  =>
                                            array(
                                                0 =>
                                                    array(
                                                        'type' => 'filler',
                                                    ),
                                                1 =>
                                                    array(
                                                        'type'         => 'box',
                                                        'layout'       => 'vertical',
                                                        'contents'     =>
                                                            array(
                                                                0 =>
                                                                    array(
                                                                        'type'       => 'image',
                                                                        'url'        => $refuel['avatar'],
                                                                        'aspectMode' => 'fit',
                                                                        'size'       => 'full',
                                                                        'offsetEnd'  => '0px',
                                                                    ),
                                                            ),
                                                        'width'        => '90px',
                                                        'height'       => '90px',
                                                        'cornerRadius' => '60px',
                                                        'borderColor'  => '#ffffff',
                                                        'borderWidth'  => '1px',
                                                        'position'     => 'relative',
                                                    ),
                                                2 =>
                                                    array(
                                                        'type' => 'filler',
                                                    ),
                                            ),
                                        'position'  => 'absolute',
                                        'offsetTop' => '30px',
                                        'width'     => '100%',
                                        'height'    => '90px',
                                    ),
                                4 =>
                                    array(
                                        'type'        => 'box',
                                        'layout'      => 'vertical',
                                        'contents'    =>
                                            array(
                                                0 =>
                                                    array(
                                                        'type'      => 'text',
                                                        'text'      => $refuel['date'],
                                                        'color'     => '#ffffff',
                                                        'align'     => 'center',
                                                        'size'      => 'xxs',
                                                        'offsetTop' => '3px',
                                                    ),
                                            ),
                                        'position'    => 'absolute',
                                        'offsetTop'   => '10px',
                                        'offsetStart' => '20px',
                                        'height'      => '25px',
                                    ),
                                5 =>
                                    array(
                                        'type'      => 'box',
                                        'layout'    => 'vertical',
                                        'contents'  =>
                                            array(
                                                0 =>
                                                    array(
                                                        'type'      => 'text',
                                                        'text'      => $refuel['time'],
                                                        'color'     => '#ffffff',
                                                        'align'     => 'end',
                                                        'size'      => 'xxs',
                                                        'offsetTop' => '3px',
                                                    ),
                                            ),
                                        'position'  => 'absolute',
                                        'offsetTop' => '10px',
                                        'height'    => '25px',
                                        'offsetEnd' => '20px',
                                    ),
                            ),
                        'paddingAll' => '0px',
                        'height'     => '285px',
                    )

            ]
        );

        foreach ($refuel['image_array'] as $image)
        {
            array_push($bubbles,

                [
                    'type' => 'bubble',
                    'body' =>
                        array(
                            'type'       => 'box',
                            'layout'     => 'vertical',
                            'contents'   =>
                                array(
                                    0 =>
                                        array(
                                            'type'        => 'image',
                                            'url'         => $background,
                                            'size'        => 'full',
                                            'aspectMode'  => 'cover',
                                            'aspectRatio' => '1:1',
                                            'gravity'     => 'top',
                                        ),
                                    1 =>
                                        array(
                                            'type'            => 'box',
                                            'layout'          => 'vertical',
                                            'contents'        =>
                                                array(
                                                    0 =>
                                                        array(
                                                            'type'         => 'box',
                                                            'layout'       => 'vertical',
                                                            'contents'     =>
                                                                array(
                                                                    0 =>
                                                                        array(
                                                                            'type'        => 'image',
                                                                            'url'         => $image['path'],
                                                                            'size'        => 'full',
                                                                            'aspectRatio' => '1:1',
                                                                            'aspectMode'  => 'cover',
                                                                            'action'      =>
                                                                                array(
                                                                                    'type'  => 'uri',
                                                                                    'label' => 'action',
                                                                                    'uri'   => $image['path'],
                                                                                ),
                                                                        ),
                                                                    1 =>
                                                                        array(
                                                                            'type'            => 'box',
                                                                            'layout'          => 'vertical',
                                                                            'contents'        =>
                                                                                array(
                                                                                    0 =>
                                                                                        array(
                                                                                            'type'  => 'text',
                                                                                            'text'  => $image['title'],
                                                                                            'color' => '#ffffff',
                                                                                        ),
                                                                                ),
                                                                            'position'        => 'absolute',
                                                                            'offsetBottom'    => '7px',
                                                                            'offsetEnd'       => '7px',
                                                                            'paddingStart'    => '25px',
                                                                            'paddingEnd'      => '25px',
                                                                            'backgroundColor' => '#00000090',
                                                                            'paddingTop'      => '4px',
                                                                            'cornerRadius'    => '7px',
                                                                            'paddingBottom'   => '4px',
                                                                        ),
                                                                ),
                                                            'cornerRadius' => '7px',
                                                            'height'       => '245px',
                                                        ),
                                                ),
                                            'position'        => 'absolute',
                                            'offsetBottom'    => '0px',
                                            'offsetStart'     => '0px',
                                            'offsetEnd'       => '0px',
                                            'offsetTop'       => '0px',
                                            'paddingAll'      => '20px',
                                            'backgroundColor' => '#00000060',
                                            'height'          => '285px',
                                        ),
                                ),
                            'paddingAll' => '0px',
                            'height'     => '285px',
                        ),
                ]);
        }

        $flexBuilder = new LINEBot\MessageBuilder\RawMessageBuilder(
            [
                'type'     => 'flex',
                'altText'  => "EEC Line's Fast Track.",
                'contents' => [
                    'type'     => 'carousel',
                    'contents' => $bubbles,
                ]]
        );

        $flexRaw =
            [
                'type'     => 'flex',
                'altText'  => "EEC Line's Fast Track.",
                'contents' => [
                    'type'     => 'carousel',
                    'contents' => $bubbles,
                ]];


        return ['to' => $refuel['lineId'], 'flex' => $flexRaw, 'flexBuilder' => $flexBuilder];
    }

    public function buildFastTrack($id)
    {
        $background = FastTrackBackground::getActiveBackground();

        $fastTrack = ImageTrackReport::with('customer', 'driver', 'vehicle', 'report')->where('id', $id)->get()->mapWithKeys(function ($item, $key) {

            $image_array = [];

            $titles = $item->report->image_title->pluck('title', 'image_index');

            foreach (json_decode($item->image_array) as $index => $image)
            {
                $image_array[$index + 1]['path']  = 'https://drive.google.com/uc?export=view&id=' . $image;
                $image_array[$index + 1]['link']  = substr($image, 10);
                $image_array[$index + 1]['title'] = $titles[$index + 1];
            }

            return [$key => ['customer'    => $item->customer->name,
                             'lineId'      => $item->customer->lineId,
                             'driver'      => $item->driver->name,
                             'id'          => $item->id,
                             'note'        => $item->note,
                             'jobId'       => $item->job_id,
                             'vehicle'     => $item->vehicle->license,
                             'report'      => $item->report->toArray(),
                             'fleet'       => Fleet::find($item->customer->fleet_id)->nameTH,
                             'date'        => $this->thaiDate(),
                             'time'        => $item->created_at->toTimeString() . ' น.',
                             'image_array' => $image_array,
            ]];
        });

        $fastTrack = $fastTrack->toArray()[0];

        $bubbles = [];

        array_push($bubbles,
            [
                'type' => 'bubble',
                'body' =>
                    array(
                        'type'       => 'box',
                        'layout'     => 'vertical',
                        'contents'   =>
                            array(
                                0 =>
                                    array(
                                        'type'        => 'image',
                                        'url'         => $background,
                                        'size'        => 'full',
                                        'aspectMode'  => 'cover',
                                        'aspectRatio' => '7:6',
                                        'gravity'     => 'top',
                                    ),
                                1 =>
                                    array(
                                        'type'            => 'box',
                                        'layout'          => 'vertical',
                                        'contents'        =>
                                            array(
                                                0 =>
                                                    array(
                                                        'type'            => 'box',
                                                        'layout'          => 'vertical',
                                                        'contents'        =>
                                                            array(
                                                                0 =>
                                                                    array(
                                                                        'type'        => 'image',
                                                                        'url'         => 'https://eecl.s3-ap-southeast-1.amazonaws.com/RichMenu-HeroFastTrackLogo5.png',
                                                                        'size'        => 'full',
                                                                        'aspectRatio' => '22:4',
                                                                        'aspectMode'  => 'fit',
                                                                    ),
                                                            ),
                                                        'backgroundColor' => '#ffffffbb',
                                                        'cornerRadius'    => '7px',
                                                    ),
                                                1 =>
                                                    array(
                                                        'type'            => 'box',
                                                        'layout'          => 'vertical',
                                                        'contents'        =>
                                                            array(
                                                                0 =>
                                                                    array(
                                                                        'type'     => 'box',
                                                                        'layout'   => 'horizontal',
                                                                        'contents' =>
                                                                            array(
                                                                                0 =>
                                                                                    array(
                                                                                        'type'  => 'text',
                                                                                        'text'  => 'ลูกค้า',
                                                                                        'size'  => 'sm',
                                                                                        'color' => '#555555',
                                                                                        'flex'  => 1,
                                                                                    ),
                                                                                1 =>
                                                                                    array(
                                                                                        'type'  => 'text',
                                                                                        'text'  => $fastTrack['customer'],
                                                                                        'size'  => 'sm',
                                                                                        'color' => '#111111',
                                                                                        'align' => 'end',
                                                                                        'flex'  => 2,
                                                                                    ),
                                                                            ),
                                                                    ),
                                                                1 =>
                                                                    array(
                                                                        'type'     => 'box',
                                                                        'layout'   => 'horizontal',
                                                                        'contents' =>
                                                                            array(
                                                                                0 =>
                                                                                    array(
                                                                                        'type'  => 'text',
                                                                                        'text'  => 'วันที่',
                                                                                        'size'  => 'sm',
                                                                                        'color' => '#555555',
                                                                                        'flex'  => 1,
                                                                                    ),
                                                                                1 =>
                                                                                    array(
                                                                                        'type'  => 'text',
                                                                                        'text'  => $fastTrack['date'],
                                                                                        'size'  => 'sm',
                                                                                        'color' => '#111111',
                                                                                        'align' => 'end',
                                                                                        'flex'  => 2,
                                                                                    ),
                                                                            ),
                                                                    ),
                                                                2 =>
                                                                    array(
                                                                        'type'     => 'box',
                                                                        'layout'   => 'horizontal',
                                                                        'contents' =>
                                                                            array(
                                                                                0 =>
                                                                                    array(
                                                                                        'type'  => 'text',
                                                                                        'text'  => 'เวลา',
                                                                                        'size'  => 'sm',
                                                                                        'color' => '#555555',
                                                                                        'flex'  => 1,
                                                                                    ),
                                                                                1 =>
                                                                                    array(
                                                                                        'type'  => 'text',
                                                                                        'text'  => $fastTrack['time'],
                                                                                        'size'  => 'sm',
                                                                                        'color' => '#111111',
                                                                                        'align' => 'end',
                                                                                        'flex'  => 2,
                                                                                    ),
                                                                            ),
                                                                    ),
                                                                3 =>
                                                                    array(
                                                                        'type'     => 'box',
                                                                        'layout'   => 'horizontal',
                                                                        'contents' =>
                                                                            array(
                                                                                0 =>
                                                                                    array(
                                                                                        'type'  => 'text',
                                                                                        'text'  => 'ฟลีท',
                                                                                        'size'  => 'sm',
                                                                                        'color' => '#555555',
                                                                                        'flex'  => 1,
                                                                                    ),
                                                                                1 =>
                                                                                    array(
                                                                                        'type'  => 'text',
                                                                                        'text'  => $fastTrack['fleet'],
                                                                                        'size'  => 'sm',
                                                                                        'color' => '#111111',
                                                                                        'align' => 'end',
                                                                                        'flex'  => 2,
                                                                                    ),
                                                                            ),
                                                                    ),
                                                            ),
                                                        'backgroundColor' => '#ffffffbb',
                                                        'cornerRadius'    => '7px',
                                                        'offsetTop'       => '5px',
                                                        'paddingAll'      => '7px',
                                                    ),
                                                2 =>
                                                    array(
                                                        'type'            => 'box',
                                                        'layout'          => 'vertical',
                                                        'contents'        =>
                                                            array(
                                                                0 =>
                                                                    array(
                                                                        'type'     => 'box',
                                                                        'layout'   => 'horizontal',
                                                                        'contents' =>
                                                                            array(
                                                                                0 =>
                                                                                    array(
                                                                                        'type'  => 'text',
                                                                                        'text'  => 'พนักงานขับรถ',
                                                                                        'size'  => 'sm',
                                                                                        'color' => '#555555',
                                                                                        'flex'  => 1,
                                                                                    ),
                                                                                1 =>
                                                                                    array(
                                                                                        'type'  => 'text',
                                                                                        'text'  => $fastTrack['driver'],
                                                                                        'size'  => 'sm',
                                                                                        'color' => '#111111',
                                                                                        'align' => 'end',
                                                                                        'flex'  => 2,
                                                                                    ),
                                                                            ),
                                                                    ),
                                                                1 =>
                                                                    array(
                                                                        'type'     => 'box',
                                                                        'layout'   => 'horizontal',
                                                                        'contents' =>
                                                                            array(
                                                                                0 =>
                                                                                    array(
                                                                                        'type'  => 'text',
                                                                                        'text'  => 'ทะเบียนรถ',
                                                                                        'size'  => 'sm',
                                                                                        'color' => '#555555',
                                                                                        'flex'  => 1,
                                                                                    ),
                                                                                1 =>
                                                                                    array(
                                                                                        'type'  => 'text',
                                                                                        'text'  => $fastTrack['vehicle'],
                                                                                        'size'  => 'sm',
                                                                                        'color' => '#111111',
                                                                                        'align' => 'end',
                                                                                        'flex'  => 2,
                                                                                    ),
                                                                            ),
                                                                    ),
                                                                2 =>
                                                                    array(
                                                                        'type'     => 'box',
                                                                        'layout'   => 'horizontal',
                                                                        'contents' =>
                                                                            array(
                                                                                0 =>
                                                                                    array(
                                                                                        'type'  => 'text',
                                                                                        'text'  => 'รายละเอียด',
                                                                                        'size'  => 'sm',
                                                                                        'color' => '#555555',
                                                                                        'flex'  => 1,
                                                                                    ),
                                                                                1 =>
                                                                                    array(
                                                                                        'type'  => 'text',
                                                                                        'text'  => $fastTrack['note'],
                                                                                        'size'  => 'sm',
                                                                                        'color' => '#111111',
                                                                                        'align' => 'end',
                                                                                        'flex'  => 2,
                                                                                    ),
                                                                            ),
                                                                    ),
                                                            ),
                                                        'backgroundColor' => '#ffffffbb',
                                                        'cornerRadius'    => '7px',
                                                        'paddingAll'      => '7px',
                                                        'offsetTop'       => '10px',
                                                    ),
                                            ),
                                        'position'        => 'absolute',
                                        'offsetBottom'    => '0px',
                                        'offsetStart'     => '0px',
                                        'offsetEnd'       => '0px',
                                        'offsetTop'       => '0px',
                                        'paddingAll'      => '20px',
                                        'backgroundColor' => '#00000040',
                                    ),
                            ),
                        'paddingAll' => '0px',
                    ),
            ]
        );

        foreach ($fastTrack['image_array'] as $image)
        {
            array_push($bubbles,

                [
                    'type' => 'bubble',
                    'body' =>
                        array(
                            'type'       => 'box',
                            'layout'     => 'vertical',
                            'contents'   =>
                                array(
                                    0 =>
                                        array(
                                            'type'        => 'image',
                                            'url'         => $background,
                                            'size'        => 'full',
                                            'aspectMode'  => 'cover',
                                            'aspectRatio' => '7:6',
                                            'gravity'     => 'top',
                                        ),
                                    1 =>
                                        array(
                                            'type'            => 'box',
                                            'layout'          => 'vertical',
                                            'contents'        =>
                                                array(
                                                    0 =>
                                                        array(
                                                            'type'         => 'box',
                                                            'layout'       => 'vertical',
                                                            'contents'     =>
                                                                array(
                                                                    0 =>
                                                                        array(
                                                                            'type'        => 'image',
                                                                            'url'         => $image['path'],
                                                                            'size'        => 'full',
                                                                            'aspectRatio' => '7:6',
                                                                            'aspectMode'  => 'cover',
                                                                            'action'      =>
                                                                                array(
                                                                                    'type'  => 'uri',
                                                                                    'label' => 'action',
                                                                                    'uri'   => $image['path'],
                                                                                ),
                                                                        ),
                                                                    1 =>
                                                                        array(
                                                                            'type'            => 'box',
                                                                            'layout'          => 'vertical',
                                                                            'contents'        =>
                                                                                array(
                                                                                    0 =>
                                                                                        array(
                                                                                            'type'  => 'text',
                                                                                            'text'  => $image['title'],
                                                                                            'color' => '#ffffff',
                                                                                        ),
                                                                                ),
                                                                            'position'        => 'absolute',
                                                                            'offsetBottom'    => '7px',
                                                                            'offsetEnd'       => '7px',
                                                                            'paddingStart'    => '25px',
                                                                            'paddingEnd'      => '25px',
                                                                            'backgroundColor' => '#00000090',
                                                                            'paddingTop'      => '4px',
                                                                            'cornerRadius'    => '7px',
                                                                            'paddingBottom'   => '4px',
                                                                        ),
                                                                ),
                                                            'cornerRadius' => '7px',
                                                        ),
                                                ),
                                            'position'        => 'absolute',
                                            'offsetBottom'    => '0px',
                                            'offsetStart'     => '0px',
                                            'offsetEnd'       => '0px',
                                            'offsetTop'       => '0px',
                                            'paddingAll'      => '20px',
                                            'backgroundColor' => '#00000040',
                                        ),
                                ),
                            'paddingAll' => '0px',
                        ),
                ]
            );
        }

        $flexBuilder = new LINEBot\MessageBuilder\RawMessageBuilder(
            [
                'type'     => 'flex',
                'altText'  => "EEC Line's Fast Track.",
                'contents' => [
                    'type'     => 'carousel',
                    'contents' => $bubbles,
                ]]
        );

        $flexRaw =
            [
                'type'     => 'flex',
                'altText'  => "EEC Line's Fast Track.",
                'contents' => [
                    'type'     => 'carousel',
                    'contents' => $bubbles,
                ]];


        return ['to' => $fastTrack['lineId'], 'flex' => $flexRaw, 'flexBuilder' => $flexBuilder];
    }

    public function buildFastTrack1($id)
    {

        $fastTrack = ImageTrackReport::with('customer', 'driver', 'vehicle', 'report')->where('id', $id)->get()->mapWithKeys(function ($item, $key) {

            $image_array = [];

            $titles = $item->report->image_title->pluck('title', 'image_index');

            foreach (json_decode($item->image_array) as $index => $image)
            {
                $image_array[$index + 1]['path']  = 'https://eecl.s3-ap-southeast-1.amazonaws.com/' . $image;
                $image_array[$index + 1]['title'] = $titles[$index + 1];
            }

            return [$key => ['customer'    => $item->customer->name,
                             'lineId'      => $item->customer->lineId,
                             'driver'      => $item->driver->name,
                             'id'          => $item->id,
                             'note'        => $item->note,
                             'jobId'       => $item->job_id,
                             'vehicle'     => $item->vehicle->license,
                             'report'      => $item->report->toArray(),
                             'fleet'       => Fleet::find($item->customer->fleet_id)->nameTH,
                             'date'        => $this->thaiDate(),
                             'time'        => Carbon::createFromTimestamp(time())->toTimeString() . ' น.',
                             'image_array' => $image_array,
            ]];
        });

        $fastTrack = $fastTrack->toArray()[0];

        $bubbles = [];

        array_push($bubbles,
            [
                'type'   => 'bubble',
                'body'   =>
                    array(
                        'type'     => 'box',
                        'layout'   => 'vertical',
                        'contents' =>
                            array(
                                0 =>
                                    array(
                                        'type'   => 'text',
                                        'text'   => 'EEC LINE',
                                        'weight' => 'bold',
                                        'color'  => '#c2000b',
                                        'size'   => 'sm',
                                    ),
                                1 =>
                                    array(
                                        'type'   => 'text',
                                        'text'   => 'Fast Track',
                                        'weight' => 'bold',
                                        'size'   => 'xxl',
                                        'margin' => 'md',
                                    ),
                                2 =>
                                    array(
                                        'type'  => 'text',
                                        'text'  => $fastTrack['customer'],
                                        'size'  => 'xs',
                                        'color' => '#aaaaaa',
                                        'wrap'  => true,
                                    ),
                                3 =>
                                    array(
                                        'type'   => 'separator',
                                        'margin' => 'xxl',
                                    ),
                                4 =>
                                    array(
                                        'type'     => 'box',
                                        'layout'   => 'vertical',
                                        'margin'   => 'xxl',
                                        'spacing'  => 'sm',
                                        'contents' =>
                                            array(
                                                0 =>
                                                    array(
                                                        'type'     => 'box',
                                                        'layout'   => 'horizontal',
                                                        'contents' =>
                                                            array(
                                                                0 =>
                                                                    array(
                                                                        'type'  => 'text',
                                                                        'text'  => 'วันที่',
                                                                        'size'  => 'sm',
                                                                        'color' => '#555555',
                                                                    ),
                                                                1 =>
                                                                    array(
                                                                        'type'  => 'text',
                                                                        'text'  => $fastTrack['date'],
                                                                        'size'  => 'sm',
                                                                        'color' => '#111111',
                                                                        'align' => 'end',
                                                                    ),
                                                            ),
                                                    ),
                                                1 =>
                                                    array(
                                                        'type'     => 'box',
                                                        'layout'   => 'horizontal',
                                                        'contents' =>
                                                            array(
                                                                0 =>
                                                                    array(
                                                                        'type'  => 'text',
                                                                        'text'  => 'เวลา',
                                                                        'size'  => 'sm',
                                                                        'color' => '#555555',
                                                                    ),
                                                                1 =>
                                                                    array(
                                                                        'type'  => 'text',
                                                                        'text'  => $fastTrack['time'],
                                                                        'size'  => 'sm',
                                                                        'color' => '#111111',
                                                                        'align' => 'end',
                                                                    ),
                                                            ),
                                                    ),
                                                2 =>
                                                    array(
                                                        'type'     => 'box',
                                                        'layout'   => 'horizontal',
                                                        'contents' =>
                                                            array(
                                                                0 =>
                                                                    array(
                                                                        'type'  => 'text',
                                                                        'text'  => 'ฟลีท',
                                                                        'size'  => 'sm',
                                                                        'color' => '#555555',
                                                                    ),
                                                                1 =>
                                                                    array(
                                                                        'type'  => 'text',
                                                                        'text'  => $fastTrack['fleet'],
                                                                        'size'  => 'sm',
                                                                        'color' => '#111111',
                                                                        'align' => 'end',
                                                                    ),
                                                            ),
                                                    ),
                                                3 =>
                                                    array(
                                                        'type'   => 'separator',
                                                        'margin' => 'xxl',
                                                    ),
                                                4 =>
                                                    array(
                                                        'type'     => 'box',
                                                        'layout'   => 'horizontal',
                                                        'contents' =>
                                                            array(
                                                                0 =>
                                                                    array(
                                                                        'type'  => 'text',
                                                                        'text'  => 'พนักงานขับรถ',
                                                                        'size'  => 'sm',
                                                                        'color' => '#555555',
                                                                        'flex'  => 0,
                                                                    ),
                                                                1 =>
                                                                    array(
                                                                        'type'  => 'text',
                                                                        'text'  => $fastTrack['driver'],
                                                                        'size'  => 'sm',
                                                                        'color' => '#111111',
                                                                        'align' => 'end',
                                                                    ),
                                                            ),
                                                        'margin'   => 'xxl',
                                                    ),
                                                5 =>
                                                    array(
                                                        'type'     => 'box',
                                                        'layout'   => 'horizontal',
                                                        'contents' =>
                                                            array(
                                                                0 =>
                                                                    array(
                                                                        'type'  => 'text',
                                                                        'text'  => 'ทะเบียนรถ',
                                                                        'size'  => 'sm',
                                                                        'color' => '#555555',
                                                                        'flex'  => 0,
                                                                    ),
                                                                1 =>
                                                                    array(
                                                                        'type'  => 'text',
                                                                        'text'  => $fastTrack['vehicle'],
                                                                        'size'  => 'sm',
                                                                        'color' => '#111111',
                                                                        'align' => 'end',
                                                                    ),
                                                            ),
                                                    ),
                                                6 =>
                                                    array(
                                                        'type'     => 'box',
                                                        'layout'   => 'horizontal',
                                                        'contents' =>
                                                            array(
                                                                0 =>
                                                                    array(
                                                                        'type'  => 'text',
                                                                        'text'  => 'รายละเอียด',
                                                                        'size'  => 'sm',
                                                                        'color' => '#555555',
                                                                        'flex'  => 0,
                                                                    ),
                                                                1 =>
                                                                    array(
                                                                        'type'  => 'text',
                                                                        'text'  => $fastTrack['note'],
                                                                        'size'  => 'sm',
                                                                        'color' => '#111111',
                                                                        'align' => 'end',
                                                                    ),
                                                            ),
                                                    ),
                                            ),
                                    ),
                                5 =>
                                    array(
                                        'type'   => 'separator',
                                        'margin' => 'xxl',
                                    ),
                                (!is_null($fastTrack['jobId'])) ?
                                    array(
                                        'type'     => 'box',
                                        'layout'   => 'horizontal',
                                        'margin'   => 'md',
                                        'contents' =>
                                            array(
                                                0 =>
                                                    array(
                                                        'type'  => 'text',
                                                        'text'  => 'JOB ID',
                                                        'size'  => 'xs',
                                                        'color' => '#aaaaaa',
                                                        'flex'  => 0,
                                                    ),
                                                1 =>
                                                    array(
                                                        'type'  => 'text',
                                                        'text'  => $fastTrack['jobId'],
                                                        'color' => '#aaaaaa',
                                                        'size'  => 'xs',
                                                        'align' => 'end',
                                                    ),
                                            ),
                                    ) : array(
                                    'type' => 'filler',
                                    'flex' => 1,
                                ),

                            ),
                    ),
                'styles' =>
                    array(
                        'footer' =>
                            array(
                                'separator' => true,
                            ),
                    ),

            ]
        );

        foreach ($fastTrack['image_array'] as $image)
        {
            array_push($bubbles,

                [
                    'type' => 'bubble',
                    'hero' =>
                        array(
                            'type'        => 'image',
                            'url'         => 'https://eecl.s3-ap-southeast-1.amazonaws.com/RichMenu-header.png',
                            'size'        => 'full',
                            'aspectMode'  => 'fit',
                            'aspectRatio' => '10:2',
                        ),
                    'body' =>
                        array(
                            'type'            => 'box',
                            'layout'          => 'vertical',
                            'contents'        =>
                                array(
                                    0 =>
                                        array(
                                            'type'     => 'box',
                                            'layout'   => 'vertical',
                                            'contents' =>
                                                array(
                                                    0 =>
                                                        array(
                                                            'type'        => 'image',
                                                            'url'         => $image['path'],
                                                            'size'        => 'full',
                                                            'aspectMode'  => 'cover',
                                                            'gravity'     => 'top',
                                                            'aspectRatio' => '16:11',
                                                            'margin'      => 'lg',
                                                        ),
                                                ),
                                            'margin'   => 'xl',
                                            'height'   => '190px',
                                        ),
                                    1 =>
                                        array(
                                            'type'      => 'text',
                                            'text'      => $image['title'],
                                            'size'      => 'xs',
                                            'margin'    => 'md',
                                            'style'     => 'italic',
                                            'align'     => 'end',
                                            'offsetEnd' => '15px',
                                        ),
                                    2 =>
                                        array(
                                            'type'       => 'box',
                                            'layout'     => 'horizontal',
                                            'contents'   =>
                                                array(
                                                    0 =>
                                                        array(
                                                            'type' => 'filler',
                                                            'flex' => 1,
                                                        ),
                                                    1 =>
                                                        array(
                                                            'type'   => 'image',
                                                            'url'    => 'https://eecl.s3-ap-southeast-1.amazonaws.com/icon_view2.png',
                                                            'size'   => 'xs',
                                                            'flex'   => 1,
                                                            'action' =>
                                                                array(
                                                                    'type'  => 'uri',
                                                                    'label' => 'action',
                                                                    'uri'   => $image['path'],
                                                                ),
                                                        ),
                                                    2 =>
                                                        array(
                                                            'type' => 'filler',
                                                            'flex' => 1,
                                                        ),
                                                    3 =>
                                                        array(
                                                            'type' => 'image',
                                                            'url'  => 'https://eecl.s3-ap-southeast-1.amazonaws.com/icon_share2.png',
                                                            'size' => 'xs',
                                                            'flex' => 1,
//                                                            'action' =>
//                                                                array(
//                                                                    'type'  => 'uri',
//                                                                    'label' => 'action',
//                                                                    'uri'   => 'http://linecorp.com/',
//                                                                ),
                                                        ),
                                                    4 =>
                                                        array(
                                                            'type' => 'filler',
                                                            'flex' => 1,
                                                        ),
                                                ),
                                            'paddingEnd' => '0px',
                                            'paddingTop' => '10px',
                                            'margin'     => 'xs',
                                        ),
                                ),
                            'paddingStart'    => '0px',
                            'paddingEnd'      => '0px',
                            'paddingTop'      => '0px',
                            'backgroundColor' => '#fafafa',
                        ),
                ]);
        }

        $flex = new LINEBot\MessageBuilder\RawMessageBuilder(
            [
                'type'     => 'flex',
                'altText'  => "EEC Line's Fast Track.",
                'contents' => [
                    'type'     => 'carousel',
                    'contents' => $bubbles,
                ]]
        );


        return $this->bot->pushMessage($fastTrack['lineId'], $flex);
    }

    public function buildLocationFlex($locations, $customer)
    {

        $flex = new LINEBot\MessageBuilder\RawMessageBuilder(
            [
                'type'     => 'flex',
                'altText'  => "EEC Line's Report.",
                'contents' =>
                    array(
                        'type'     => 'carousel',
                        'contents' =>
                            array(
                                0 =>
                                    array(
                                        'type' => 'bubble',
                                        'hero' =>
                                            array(
                                                'type'        => 'image',
                                                'url'         => 'https://eecl.s3-ap-southeast-1.amazonaws.com/header_eecl.png',
                                                'size'        => 'full',
                                                'aspectRatio' => '20:9',
                                                'aspectMode'  => 'cover',
                                                'offsetTop'   => '-1px'
                                            ),
                                        'body' =>
                                            array(
                                                'type'     => 'box',
                                                'layout'   => 'vertical',
                                                'spacing'  => 'md',
                                                'contents' =>
                                                    array(
                                                        0 =>
                                                            array(
                                                                'type'    => 'text',
                                                                'text'    => $customer['name'],
                                                                'wrap'    => true,
                                                                'weight'  => 'bold',
                                                                'gravity' => 'center',
                                                                'size'    => 'xl',
                                                            ),
                                                        1 =>
                                                            array(
                                                                'type'     => 'box',
                                                                'layout'   => 'vertical',
                                                                'margin'   => 'lg',
                                                                'spacing'  => 'sm',
                                                                'contents' =>
                                                                    array(
                                                                        0 =>
                                                                            array(
                                                                                'type'     => 'box',
                                                                                'layout'   => 'baseline',
                                                                                'spacing'  => 'sm',
                                                                                'contents' =>
                                                                                    array(
                                                                                        0 =>
                                                                                            array(
                                                                                                'type'  => 'text',
                                                                                                'text'  => 'วันที่',
                                                                                                'color' => '#aaaaaa',
                                                                                                'size'  => 'sm',
                                                                                                'flex'  => 1,
                                                                                            ),
                                                                                        1 =>
                                                                                            array(
                                                                                                'type'  => 'text',
                                                                                                'text'  => $this->thaiDate(),
                                                                                                'wrap'  => true,
                                                                                                'size'  => 'sm',
                                                                                                'color' => '#666666',
                                                                                                'flex'  => 2,
                                                                                            ),
                                                                                    ),
                                                                            ),
                                                                        1 =>
                                                                            array(
                                                                                'type'     => 'box',
                                                                                'layout'   => 'baseline',
                                                                                'spacing'  => 'sm',
                                                                                'contents' =>
                                                                                    array(
                                                                                        0 =>
                                                                                            array(
                                                                                                'type'  => 'text',
                                                                                                'text'  => 'ฟลีท',
                                                                                                'color' => '#aaaaaa',
                                                                                                'size'  => 'sm',
                                                                                                'flex'  => 1,
                                                                                            ),
                                                                                        1 =>
                                                                                            array(
                                                                                                'type'  => 'text',
                                                                                                'text'  => $customer['fleet'],
                                                                                                'wrap'  => true,
                                                                                                'size'  => 'sm',
                                                                                                'color' => '#666666',
                                                                                                'flex'  => 2,
                                                                                            ),
                                                                                    ),
                                                                            ),
                                                                        2 =>
                                                                            array(
                                                                                'type'     => 'box',
                                                                                'layout'   => 'baseline',
                                                                                'spacing'  => 'sm',
                                                                                'contents' =>
                                                                                    array(
                                                                                        0 =>
                                                                                            array(
                                                                                                'type'  => 'text',
                                                                                                'text'  => 'จำนวนรถ',
                                                                                                'color' => '#aaaaaa',
                                                                                                'size'  => 'sm',
                                                                                                'flex'  => 1,
                                                                                            ),
                                                                                        1 =>
                                                                                            array(
                                                                                                'type'  => 'text',
                                                                                                'text'  => count($locations) . ' คัน',
                                                                                                'wrap'  => true,
                                                                                                'color' => '#666666',
                                                                                                'size'  => 'sm',
                                                                                                'flex'  => 2,
                                                                                            ),
                                                                                    ),
                                                                            ),
                                                                    ),
                                                            ),
                                                        2 =>
                                                            array(
                                                                'type'         => 'box',
                                                                'layout'       => 'vertical',
                                                                'margin'       => 'xxl',
                                                                'contents'     =>
                                                                    array(
                                                                        0 =>
                                                                            array(
                                                                                'type' => 'separator',
                                                                            ),
                                                                        1 =>
                                                                            array(
                                                                                'type'   => 'text',
                                                                                'text'   => '© Copyright 2020 EEC LINE Co., Ltd. - All Rights Reserved',
                                                                                'color'  => '#aaaaaa',
                                                                                'wrap'   => true,
                                                                                'margin' => 'md',
                                                                                'size'   => 'xs',
                                                                            ),
                                                                    ),
                                                                'offsetBottom' => '15px',
                                                                'offsetStart'  => '20px',
                                                                'position'     => 'absolute',
                                                                'paddingEnd'   => '15px',
                                                            ),
                                                    ),
                                                'height'   => '230px',
                                            ),
                                    ),
                                1 =>
                                    array(
                                        'type'   => 'bubble',
                                        'header' =>
                                            array(
                                                'type'     => 'box',
                                                'layout'   => 'vertical',
                                                'contents' =>
                                                    array(
                                                        0 =>
                                                            array(
                                                                'type'        => 'image',
                                                                'url'         => 'https://cdn.hipwallpaper.com/m/19/46/ykclAO.jpg',
                                                                'position'    => 'absolute',
                                                                'offsetTop'   => '0px',
                                                                'offsetStart' => '0px',
                                                                'margin'      => 'none',
                                                                'align'       => 'start',
                                                                'aspectMode'  => 'cover',
                                                                "aspectRatio" => "73:45",
                                                                'size'        => 'full',
                                                            ),
                                                        1 =>
                                                            array(
                                                                'type'   => 'text',
                                                                'text'   => 'REPORT',
                                                                'color'  => '#ffffff',
                                                                'margin' => 'none',
                                                                'weight' => 'bold',
                                                            ),
                                                    ),
                                                'width'    => '500px'
                                            ),
                                        'body'   =>
                                            array(
                                                'type'     => 'box',
                                                'layout'   => 'vertical',
                                                'contents' => $this->buildGPS($locations),
                                                'margin'   => 'none',
                                                'spacing'  => 'none',
                                            ),
                                        'styles' =>
                                            array(
                                                'header' =>
                                                    array(
                                                        'separator'       => false,
                                                        'backgroundColor' => '#c2000b',
                                                    ),
                                            ),
                                    ),
                            ),
                    )
            ]);

        return $this->bot->pushMessage($customer['lineId'], $flex);
    }

    public function buildNotUpdateTireReport($fleet_id)
    {
        function countAllVehicle($data)
        {
            $count = 0;
            foreach ($data as $datum)
            {
                $count += count($datum['vehicle']);
            }

            return $count;
        }

        function buildBranchBubble($fleet, $fleet_name)
        {
            $content = [];

            foreach ($fleet as $key => $driver)
            {
                $vehicles = "";

                foreach ($driver['vehicle'] as $vehicle)
                {
                    $vehicles .= ', ' . $vehicle;
                }

                $flex_drivers_vehicle =
                    [
                        'type'          => 'box',
                        'layout'        => 'horizontal',
                        'contents'      =>
                            array(
                                0 =>
                                    array(
                                        'type'    => 'text',
                                        'text'    => $driver['name'],
                                        'align'   => 'start',
                                        'size'    => 'xs',
                                        'gravity' => 'center',
                                    ),
                                1 =>
                                    array(
                                        'type'  => 'text',
                                        'text'  => substr($vehicles, 2),
                                        'align' => 'center',
                                        'size'  => 'xs',
                                        'wrap'  => true,
                                    ),
                            ),
                        'paddingTop'    => '5px',
                        'paddingBottom' => '5px',
                    ];

                array_push($content, $flex_drivers_vehicle);
                array_push($content, ['type' => 'separator', 'color' => '#bbbbbb']);

            }

            return
                [
                    'type'     => 'flex',
                    'altText'  => "EEC Line's Report.",
                    'contents' =>
                        array(
                            'type'   => 'bubble',
                            'body'   =>
                                array(
                                    'type'         => 'box',
                                    'layout'       => 'vertical',
                                    'contents'     =>
                                        array(
                                            0 =>
                                                array(
                                                    'type'          => 'box',
                                                    'layout'        => 'horizontal',
                                                    'contents'      =>
                                                        array(
                                                            0 =>
                                                                array(
                                                                    'type'  => 'image',
                                                                    'url'   => 'https://drive.google.com/uc?export=view&id=1jqpURFf3M3MkocXnYfZP1ekBj2oCb5Yb',
                                                                    'align' => 'center',
                                                                    'flex'  => 2,
                                                                    'size'  => 'xxs',
                                                                ),
                                                            1 =>
                                                                array(
                                                                    'type'    => 'text',
                                                                    'text'    => 'รายงานไม่ตรวจยางประจำสัปดาห์',
                                                                    'gravity' => 'center',
                                                                    'flex'    => 9,
                                                                    'size'    => 'md',
                                                                    'color'   => '#ffffff',
                                                                    'align'   => 'center',
                                                                ),
                                                        ),
                                                    'paddingBottom' => '5px',
                                                ),
                                            1 =>
                                                array(
                                                    'type'     => 'box',
                                                    'layout'   => 'vertical',
                                                    'contents' =>
                                                        array(
                                                            0 =>
                                                                array(
                                                                    'type'  => 'text',
                                                                    'text'  => 'สาขา ' . $fleet_name,
                                                                    'color' => '#ffffff',
                                                                    'size'  => 'sm',
                                                                    'align' => 'center',
                                                                ),
                                                        ),
                                                ),
                                            2 =>
                                                array(
                                                    'type'            => 'box',
                                                    'layout'          => 'vertical',
                                                    'contents'        => [
                                                        [
                                                            'type'         => 'box',
                                                            'layout'       => 'horizontal',
                                                            'paddingEnd'   => '10px',
                                                            'paddingStart' => '10px',
                                                            'contents'     =>
                                                                [
                                                                    [
                                                                        'type'  => 'text',
                                                                        'text'  => 'ชื่อ',
                                                                        'align' => 'start',
                                                                        'size'  => 'xs',
                                                                    ],
                                                                    [
                                                                        'type'  => 'text',
                                                                        'text'  => 'ทะเบียน',
                                                                        'align' => 'center',
                                                                        'size'  => 'xs',
                                                                    ],
                                                                ],
                                                        ],
                                                        [
                                                            'type'  => 'separator',
                                                            'color' => '#000000',
                                                        ],
                                                        [
                                                            'type'         => 'box',
                                                            'layout'       => 'vertical',
                                                            'contents'     => $content,
                                                            'paddingStart' => '10px',
                                                            'paddingEnd'   => '10px',
                                                        ]
                                                    ],
                                                    'backgroundColor' => '#ffffff',
                                                    'cornerRadius'    => '5px',
                                                    'paddingAll'      => '5px',
                                                    'paddingEnd'      => '10px',
                                                    'paddingStart'    => '10px',
                                                ),
                                        ),
                                    'paddingStart' => '10px',
                                    'paddingEnd'   => '10px',
                                    'paddingTop'   => '15px',
                                ),
                            'styles' =>
                                array(
                                    'body' =>
                                        array(
                                            'backgroundColor' => '#c2000b',
                                        ),
                                ),
                        )
                ];

        }

        $controller = new \App\Http\Controllers\API\DriverController();
        $data       = $controller->getDriverNotUpdateTireThisWeek();


        $lcbDrivers  = count($data['แหลมฉบัง']);
        $lcbVehicles = countAllVehicle($data['แหลมฉบัง']);
        $mkDrivers   = count($data['มาบข่า']);
        $mkVehicles  = countAllVehicle($data['มาบข่า']);
        $mkcDrivers  = count($data['มาบข่าเคมี']);
        $mkcVehicles = countAllVehicle($data['มาบข่าเคมี']);
        $sswDrivers  = count($data['สุขสวัสดิ์']);
        $sswVehicles = countAllVehicle($data['สุขสวัสดิ์']);
        $allDrivers  = $lcbDrivers + $mkDrivers + $sswDrivers + $mkcDrivers;
        $allVehicles = $lcbVehicles + $mkVehicles + $sswVehicles + $mkcVehicles;
        $summarize   = [
            'type'     => 'flex',
            'altText'  => "EEC Line's Report.",
            'contents' => array(
                'type'   => 'bubble',
                'body'   =>
                    array(
                        'type'         => 'box',
                        'layout'       => 'vertical',
                        'contents'     =>
                            array(
                                0 =>
                                    array(
                                        'type'     => 'box',
                                        'layout'   => 'horizontal',
                                        'contents' =>
                                            array(
                                                0 =>
                                                    array(
                                                        'type'  => 'image',
                                                        'url'   => 'https://drive.google.com/uc?export=view&id=1jqpURFf3M3MkocXnYfZP1ekBj2oCb5Yb',
                                                        'align' => 'center',
                                                        'flex'  => 2,
                                                        'size'  => 'xxs',
                                                    ),
                                                1 =>
                                                    array(
                                                        'type'    => 'text',
                                                        'text'    => 'รายงานไม่ตรวจยางประจำสัปดาห์',
                                                        'gravity' => 'center',
                                                        'flex'    => 9,
                                                        'size'    => 'md',
                                                        'color'   => '#ffffff',
                                                        'align'   => 'center',
                                                    ),
                                            ),
                                    ),
                                1 =>
                                    array(
                                        'type'       => 'box',
                                        'layout'     => 'vertical',
                                        'contents'   =>
                                            array(
                                                0 =>
                                                    array(
                                                        'type'      => 'text',
                                                        'text'      => 'จำนวพขร. ไม่ตรวจยางทั้งหมด ' . $allDrivers . ' คน',
                                                        'color'     => '#ffffff',
                                                        'size'      => 'sm',
                                                        'align'     => 'center',
                                                        'offsetEnd' => '5px',
                                                    ),
                                                1 =>
                                                    array(
                                                        'type'            => 'box',
                                                        'layout'          => 'vertical',
                                                        'contents'        =>
                                                            array(
                                                                array(
                                                                    'type'         => 'box',
                                                                    'layout'       => 'horizontal',
                                                                    'paddingEnd'   => '10px',
                                                                    'paddingStart' => '10px',
                                                                    'contents'     =>
                                                                        array(
                                                                            0 =>
                                                                                array(
                                                                                    'type'  => 'text',
                                                                                    'text'  => 'สาขา',
                                                                                    'align' => 'start',
                                                                                    'size'  => 'xs',
                                                                                    'flex'  => 2,
                                                                                ),
                                                                            1 =>
                                                                                array(
                                                                                    'type'  => 'text',
                                                                                    'text'  => 'จำนวนพขร. ไม่ทำหน้าที่(คน)',
                                                                                    'align' => 'center',
                                                                                    'size'  => 'xs',
                                                                                    'flex'  => 6,
                                                                                ),
                                                                        ),
                                                                ),
                                                                array(
                                                                    'type'  => 'separator',
                                                                    'color' => '#000000',
                                                                ),
                                                                array(
                                                                    'type'          => 'box',
                                                                    'layout'        => 'horizontal',
                                                                    'contents'      =>
                                                                        array(
                                                                            0 =>
                                                                                array(
                                                                                    'type'  => 'text',
                                                                                    'text'  => 'แหลมฉบัง',
                                                                                    'align' => 'start',
                                                                                    'size'  => 'xs',
                                                                                    'flex'  => 2,
                                                                                ),
                                                                            1 =>
                                                                                array(
                                                                                    'type'  => 'text',
                                                                                    'text'  => '' . $lcbDrivers,
                                                                                    'align' => 'center',
                                                                                    'size'  => 'xs',
                                                                                    'flex'  => 6,
                                                                                ),
                                                                        ),
                                                                    'paddingEnd'    => '10px',
                                                                    'paddingStart'  => '10px',
                                                                    'paddingTop'    => '5px',
                                                                    'paddingBottom' => '5px',
                                                                ),
                                                                ['type' => 'separator', 'color' => '#bbbbbb'],
                                                                array(
                                                                    'type'          => 'box',
                                                                    'layout'        => 'horizontal',
                                                                    'contents'      =>
                                                                        array(
                                                                            0 =>
                                                                                array(
                                                                                    'type'  => 'text',
                                                                                    'text'  => 'มาบข่า',
                                                                                    'align' => 'start',
                                                                                    'size'  => 'xs',
                                                                                    'flex'  => 2,
                                                                                ),
                                                                            1 =>
                                                                                array(
                                                                                    'type'  => 'text',
                                                                                    'text'  => '' . $mkDrivers,
                                                                                    'align' => 'center',
                                                                                    'size'  => 'xs',
                                                                                    'flex'  => 6,
                                                                                ),
                                                                        ),
                                                                    'paddingEnd'    => '10px',
                                                                    'paddingStart'  => '10px',
                                                                    'paddingTop'    => '5px',
                                                                    'paddingBottom' => '5px',
                                                                ),
                                                                ['type' => 'separator', 'color' => '#bbbbbb'],
                                                                array(
                                                                    'type'          => 'box',
                                                                    'layout'        => 'horizontal',
                                                                    'contents'      =>
                                                                        array(
                                                                            0 =>
                                                                                array(
                                                                                    'type'  => 'text',
                                                                                    'text'  => 'สุขสวัสดิ์',
                                                                                    'align' => 'start',
                                                                                    'size'  => 'xs',
                                                                                    'flex'  => 2,
                                                                                ),
                                                                            1 =>
                                                                                array(
                                                                                    'type'  => 'text',
                                                                                    'text'  => '' . $sswDrivers,
                                                                                    'align' => 'center',
                                                                                    'size'  => 'xs',
                                                                                    'flex'  => 6,
                                                                                ),
                                                                        ),
                                                                    'paddingEnd'    => '10px',
                                                                    'paddingStart'  => '10px',
                                                                    'paddingTop'    => '5px',
                                                                    'paddingBottom' => '5px',
                                                                ),
                                                            ),
                                                        'backgroundColor' => '#ffffff',
                                                        'cornerRadius'    => '5px',
                                                        'paddingAll'      => '5px',
                                                        'paddingStart'    => '10px',
                                                        'paddingEnd'      => '10px',
                                                    ),
                                            ),
                                        'paddingTop' => '10px',
                                    ),
                                2 =>
                                    array(
                                        'type'       => 'box',
                                        'layout'     => 'vertical',
                                        'contents'   =>
                                            array(
                                                0 =>
                                                    array(
                                                        'type'      => 'text',
                                                        'text'      => 'จำนวนรถไม่ตรวจยาง ' . $allVehicles . ' คัน',
                                                        'color'     => '#ffffff',
                                                        'size'      => 'sm',
                                                        'align'     => 'center',
                                                        'offsetEnd' => '5px',
                                                    ),
                                                1 =>
                                                    array(
                                                        'type'            => 'box',
                                                        'layout'          => 'vertical',
                                                        'contents'        =>
                                                            array(
                                                                array(
                                                                    'type'         => 'box',
                                                                    'layout'       => 'horizontal',
                                                                    'paddingEnd'   => '10px',
                                                                    'paddingStart' => '10px',
                                                                    'contents'     =>
                                                                        array(
                                                                            0 =>
                                                                                array(
                                                                                    'type'  => 'text',
                                                                                    'text'  => 'สาขา',
                                                                                    'align' => 'start',
                                                                                    'size'  => 'xs',
                                                                                    'flex'  => 2,
                                                                                ),
                                                                            1 =>
                                                                                array(
                                                                                    'type'  => 'text',
                                                                                    'text'  => 'จำนวนรถไม่ตรวจยาง(คัน)',
                                                                                    'align' => 'center',
                                                                                    'size'  => 'xs',
                                                                                    'flex'  => 6,
                                                                                ),
                                                                        ),
                                                                ),
                                                                array(
                                                                    'type'  => 'separator',
                                                                    'color' => '#000000',
                                                                ),
                                                                array(
                                                                    'type'          => 'box',
                                                                    'layout'        => 'horizontal',
                                                                    'contents'      =>
                                                                        array(
                                                                            0 =>
                                                                                array(
                                                                                    'type'  => 'text',
                                                                                    'text'  => 'แหลมฉบัง',
                                                                                    'align' => 'start',
                                                                                    'size'  => 'xs',
                                                                                    'flex'  => 2,
                                                                                ),
                                                                            1 =>
                                                                                array(
                                                                                    'type'  => 'text',
                                                                                    'text'  => '' . $lcbVehicles,
                                                                                    'align' => 'center',
                                                                                    'size'  => 'xs',
                                                                                    'flex'  => 6,
                                                                                ),
                                                                        ),
                                                                    'paddingEnd'    => '10px',
                                                                    'paddingStart'  => '10px',
                                                                    'paddingTop'    => '5px',
                                                                    'paddingBottom' => '5px',
                                                                ),
                                                                ['type' => 'separator', 'color' => '#bbbbbb'],
                                                                array(
                                                                    'type'          => 'box',
                                                                    'layout'        => 'horizontal',
                                                                    'contents'      =>
                                                                        array(
                                                                            0 =>
                                                                                array(
                                                                                    'type'  => 'text',
                                                                                    'text'  => 'มาบข่า',
                                                                                    'align' => 'start',
                                                                                    'size'  => 'xs',
                                                                                    'flex'  => 2,
                                                                                ),
                                                                            1 =>
                                                                                array(
                                                                                    'type'  => 'text',
                                                                                    'text'  => '' . $mkVehicles,
                                                                                    'align' => 'center',
                                                                                    'size'  => 'xs',
                                                                                    'flex'  => 6,
                                                                                ),
                                                                        ),
                                                                    'paddingEnd'    => '10px',
                                                                    'paddingStart'  => '10px',
                                                                    'paddingTop'    => '5px',
                                                                    'paddingBottom' => '5px',
                                                                ),
                                                                ['type' => 'separator', 'color' => '#bbbbbb'],
                                                                array(
                                                                    'type'          => 'box',
                                                                    'layout'        => 'horizontal',
                                                                    'contents'      =>
                                                                        array(
                                                                            0 =>
                                                                                array(
                                                                                    'type'  => 'text',
                                                                                    'text'  => 'สุขสวัสดิ์',
                                                                                    'align' => 'start',
                                                                                    'size'  => 'xs',
                                                                                    'flex'  => 2,
                                                                                ),
                                                                            1 =>
                                                                                array(
                                                                                    'type'  => 'text',
                                                                                    'text'  => '' . $sswVehicles,
                                                                                    'align' => 'center',
                                                                                    'size'  => 'xs',
                                                                                    'flex'  => 6,
                                                                                ),
                                                                        ),
                                                                    'paddingEnd'    => '10px',
                                                                    'paddingStart'  => '10px',
                                                                    'paddingTop'    => '5px',
                                                                    'paddingBottom' => '5px',
                                                                ),
                                                            ),
                                                        'backgroundColor' => '#ffffff',
                                                        'cornerRadius'    => '5px',
                                                        'paddingAll'      => '5px',
                                                        'paddingStart'    => '10px',
                                                        'paddingEnd'      => '10px',
                                                    ),
                                            ),
                                        'paddingTop' => '20px',
                                    ),
                            ),
                        'paddingStart' => '10px',
                        'paddingEnd'   => '10px',
                        'paddingTop'   => '15px',
                    ),
                'styles' =>
                    array(
                        'body' =>
                            array(
                                'backgroundColor' => '#c2000b',
                            ),
                    ),
            )
        ];


        switch ($fleet_id)
        {
            case 0:
                $bubbles = $summarize;
                $to      = LineGroup::find(55)->lineId;
                break;
            case 1:
                $bubbles = buildBranchBubble($data['มาบข่า'], 'มาบข่า');
                $to      = LineGroup::find(4)->lineId;
                break;
            case 2:
                $bubbles = buildBranchBubble($data['สุขสวัสดิ์'], 'สุขสวัสดิ์');
                $to      = LineGroup::find(2)->lineId;
                break;
            case 3:
                $bubbles = buildBranchBubble($data['แหลมฉบัง'], 'แหลมฉบัง');
                $to      = LineGroup::find(5)->lineId;
                break;
            case 4:
                $bubbles = buildBranchBubble($data['มาบข่า'], 'มาบข่า');
                $to      = LineGroup::find(58)->lineId;
                break;
            case 5:
                $bubbles = buildBranchBubble($data['มาบข่าเคมี'], 'มาบข่าเคมี');
                $to      = LineGroup::find(60)->lineId;
                break;
        }

        $flexRaw = $bubbles;

        $flexBuilder = new LINEBot\MessageBuilder\RawMessageBuilder($flexRaw);

//        $this->bot->pushMessage($to, $flexBuilder);

        return ['to' => $to, 'flex' => $flexRaw, 'flexBuilder' => $flexBuilder];

    }

    public function buildGPS($locations)
    {

        $gps = [];

        foreach ($locations as $location)
        {

            array_push($gps,
                [
                    'type'        => 'box',
                    'layout'      => 'baseline',
                    'spacing'     => 'none',
                    'contents'    =>
                        [
                            [
                                'type' => 'icon',
                                'url'  => ($location['io_name'] === 'จอด') ? 'https://eecl.co.th/img/red_circle.png' : 'https://eecl.co.th/img/green_circle.png',
                                'size' => 'xxs',
                            ],
                            [
                                'type'   => 'text',
                                'text'   => 'ทะเบียน:',
                                'color'  => '#aaaaaa',
                                'size'   => 'sm',
                                'flex'   => 1,
                                'margin' => 'lg',
                            ],
                            [
                                'type'  => 'text',
                                'text'  => $location['licenseplate'],
                                'wrap'  => true,
                                'size'  => 'sm',
                                'color' => '#666666',
                                'flex'  => 2,
                            ],
                            [
                                'type'      => 'icon',
                                'url'       => 'https://eecl.co.th/img/position-pin@3x.png',
                                'size'      => 'xl',
                                'offsetTop' => '5px',
                                'position'  => 'relative',
                                'offsetEnd' => '15px',
                            ],
                        ],
                    'margin'      => 'md',
                    'offsetStart' => '7px',
                    "action"      => [
                        "type"  => "uri",
                        "label" => "action",
                        "uri"   => 'https://maps.google.com?q=' . $location['latitude'] . ',' . $location['longitude'] . '&hl=en-US&gl=us',
                    ]
                ]);
            array_push($gps,
                [
                    'type'   => 'separator',
                    'margin' => 'xl',
                ]
            );
        }

        return $gps;
    }

    public function buildTimesDriverDoTirePressureJob($fleet_id)
    {
        function genBranchDetail($branch_id, $branch_name, $data)
        {
            $detailContent = [
                [
                    'type'         => 'box',
                    'layout'       => 'baseline',
                    'contents'     =>
                        [
                            ['type' => 'text', 'align' => 'center', 'size' => 'xs', 'text' => 'ชื่อ - นามสกุล', 'flex' => 5],
                            ['type' => 'text', 'text' => 'สัปดาห์นี้', 'align' => 'center', 'size' => 'xs', 'flex' => 2],
                            ['type' => 'text', 'text' => 'จำนวนสะสม', 'align' => 'center', 'size' => 'xs', 'flex' => 3],
                        ],
                    'paddingStart' => '5px',
                    'paddingTop'   => '5px',
                ],
            ];

            foreach ($data as $key => $value)
            {
                if ($value['fleet_id'] === $branch_id)
                {
                    $detail = [
                        'type'         => 'box',
                        'layout'       => 'baseline',
                        'contents'     =>
                            [
                                ['type' => 'text', 'text' => $value['firstName'] . ' ' . $value['lastName'], 'size' => 'xs', 'flex' => 5],
                                ['type' => 'icon', 'url' => $value['do_this_week'] ? 'https://drive.google.com/uc?export=view&id=1fqEyU1cbA4TbvrzklF6wGST9grtvDsmj' : 'https://drive.google.com/uc?export=view&id=1XMznCxILAVhGbUyZbMKtTQDi3deUaXP8',],
                                ['type' => 'text', 'text' => $value['amount'] . '/' . $value['totalWeek'], 'align' => 'center', 'size' => 'xs', 'flex' => 3],
                            ],
                        'paddingStart' => '5px',
                        'paddingTop'   => '5px',
                    ];
                    array_push($detailContent, ['type' => 'separator', 'color' => '#bbbbbb']);
                    array_push($detailContent, $detail);
                }

            }

            $content = [
                [
                    'type'     => 'box',
                    'layout'   => 'vertical',
                    'contents' => [['type' => 'text', 'text' => 'สาขา' . $branch_name, 'color' => '#ffffff', 'size' => 'sm', 'align' => 'center', 'offsetEnd' => '5px']],
                ],
                [
                    'type'            => 'box',
                    'layout'          => 'vertical',
                    'contents'        => $detailContent,
                    'backgroundColor' => '#ffffff',
                    'cornerRadius'    => '5px',
                    'paddingAll'      => '5px',
                    'paddingStart'    => '10px',
                    'paddingEnd'      => '10px',
                ],
            ];

            return ['type' => 'box', 'layout' => 'vertical', 'contents' => $content, 'paddingTop' => '15px'];

        }

        $to         = null;
        $controller = new \App\Http\Controllers\API\TirePressureAndTreadController();
        $data       = $controller->timesDriverDoTirePressureJob();
        $content    = [
            [
                'type'     => 'box',
                'layout'   => 'horizontal',
                'contents' =>
                    [
                        ['type' => 'image', 'url' => 'https://drive.google.com/uc?export=view&id=1jqpURFf3M3MkocXnYfZP1ekBj2oCb5Yb', 'align' => 'center', 'flex' => 2, 'size' => 'xxs'],
                        ['type' => 'text', 'text' => 'ความร่วมมือวัดดอกและเติมลมยาง', 'gravity' => 'center', 'flex' => 9, 'size' => 'sm', 'color' => '#ffffff', 'align' => 'center']
                    ]
            ],
        ];

        switch ($fleet_id)
        {
            case 0:
                array_push($content, genBranchDetail(1, 'มาบข่า', $data));
                array_push($content, genBranchDetail(4, 'มาบข่าเคมี', $data));

                $to = LineGroup::find(55)->lineId;
                break;
            case 1:
                array_push($content, genBranchDetail(1, 'มาบข่า', $data));
                $to = LineGroup::find(4)->lineId;
                break;
            case 2:
                array_push($content, genBranchDetail(2, 'สุขสวัสดิ์', $data));
                $to = LineGroup::find(2)->lineId;
                break;
            case 3:
                array_push($content, genBranchDetail(3, 'แหลมฉบัง', $data));
                $to = LineGroup::find(5)->lineId;
                break;
            case 4:
                array_push($content, genBranchDetail(1, 'มาบข่า', $data));
                $to = LineGroup::find(58)->lineId;
                break;
            case 5:
                array_push($content, genBranchDetail(4, 'มาบข่าเคมี', $data));
                $to = LineGroup::find(60)->lineId;
                break;
            case 6:
                array_push($content, genBranchDetail(2, 'สุขสวัสดิ์', $data));
                array_push($content, genBranchDetail(3, 'แหลมฉบัง', $data));

                $to = LineGroup::find(55)->lineId;
                break;
        }

        $flexRaw = [
            'type'     => 'flex',
            'altText'  => "EEC LINE's Report.",
            'contents' => [
                'type'   => 'bubble',
                'body'   => ['type' => 'box', 'layout' => 'vertical', 'contents' => $content, 'paddingStart' => '10px', 'paddingEnd' => '10px', 'paddingTop' => '15px'],
                'styles' => ['body' => ['backgroundColor' => '#262262']],
            ]
        ];

        $flexBuilder = new LINEBot\MessageBuilder\RawMessageBuilder($flexRaw);

        return ['to' => $to, 'flex' => $flexRaw, 'flexBuilder' => $flexBuilder];
//        dd($this->bot->pushMessage('U44767feea99ac5dc98ec9268db9ce0f9', $flexBuilder));
    }

    public function buildMaintenanceApprovalReceipt($maintenance_approval_id, $role)
    {
        function driverGroupId($fleet_id)
        {
            $group_id = null;
            switch ($fleet_id)
            {
                case 1:
                    $group_id = 4;
                    break;
                case 2:
                    $group_id = 2;
                    break;
                case 3:
                    $group_id = 5;
                    break;
                default:
                    break;
            }

            return $group_id;
        }

        $data                        = MaintenanceApproval::find($maintenance_approval_id);
        $maintenance_approval_status = Status::find($data->status_id)->nameTH;
        $license                     = Vehicle::find($data->vehicle_id)->license;
        $requester_name              = ((Driver::find($data->requester_id)->firstName) . ' ' . (Driver::find($data->requester_id)->lastName));
        $link                        = null;
        $to                          = null;

        switch ($role)
        {
            case 'driver':
                $link            = 'line://app/1653575237-yDkDbm1r?tab=maintenance-approval-approver';
                $driver_fleet_id = Driver::find($data->requester_id)->first()->fleet_id;
                $to              = LineGroup::find(driverGroupId($driver_fleet_id))->lineId;
                break;
            case 'approver':
                $link = 'line://app/1653575237-yDkDbm1r?tab=maintenance-approval-technician-job';
                $to   = LineGroup::find(59)->lineId;
                break;
            case 'technician':
                $link = 'line://app/1653575237-yDkDbm1r?tab=maintenance-approval-driver';
                $to   = Driver::find($data->requester_id)->first()->lineId;
                break;
            default:
                break;
        }

        $content = [
            'type' => 'bubble',
            'body' =>
                array(
                    'type'     => 'box',
                    'layout'   => 'vertical',
                    'contents' =>
                        array(
                            0 =>
                                array(
                                    'type'   => 'text',
                                    'text'   => 'ใบแจ้งซ่อม',
                                    'weight' => 'bold',
                                    'size'   => 'xxl',
                                    'margin' => 'none',
                                ),
                            1 =>
                                array(
                                    'type'  => 'text',
                                    'text'  => 'ใบแจ้งซ่อมเลขที่ : #' . $data->id,
                                    'size'  => 'xs',
                                    'color' => '#aaaaaa',
                                    'wrap'  => true,
                                ),
                            2 =>
                                array(
                                    'type'   => 'separator',
                                    'margin' => 'xxl',
                                ),
                            3 =>
                                array(
                                    'type'     => 'box',
                                    'layout'   => 'vertical',
                                    'margin'   => 'xxl',
                                    'spacing'  => 'sm',
                                    'contents' =>
                                        array(
                                            0 =>
                                                array(
                                                    'type'     => 'box',
                                                    'layout'   => 'horizontal',
                                                    'contents' =>
                                                        array(
                                                            0 =>
                                                                array(
                                                                    'type'  => 'text',
                                                                    'text'  => 'สถานะ',
                                                                    'size'  => 'sm',
                                                                    'color' => '#555555',
                                                                    'flex'  => 3,
                                                                ),
                                                            1 =>
                                                                array(
                                                                    'type'  => 'text',
                                                                    'text'  => $maintenance_approval_status,
                                                                    'size'  => 'sm',
                                                                    'color' => '#111111',
                                                                    'align' => 'end',
                                                                    'flex'  => 9,
                                                                ),
                                                        ),
                                                ),
                                            1 =>
                                                array(
                                                    'type'     => 'box',
                                                    'layout'   => 'horizontal',
                                                    'contents' =>
                                                        array(
                                                            0 =>
                                                                array(
                                                                    'type'  => 'text',
                                                                    'text'  => 'ผู้แจ้งซ่อม',
                                                                    'size'  => 'sm',
                                                                    'color' => '#555555',
                                                                    'flex'  => 3,
                                                                ),
                                                            1 =>
                                                                array(
                                                                    'type'  => 'text',
                                                                    'text'  => $requester_name,
                                                                    'size'  => 'sm',
                                                                    'color' => '#111111',
                                                                    'align' => 'end',
                                                                    'flex'  => 9,
                                                                ),
                                                        ),
                                                ),
                                            2 =>
                                                array(
                                                    'type'     => 'box',
                                                    'layout'   => 'horizontal',
                                                    'contents' =>
                                                        array(
                                                            0 =>
                                                                array(
                                                                    'type'  => 'text',
                                                                    'text'  => 'ทะเบียน',
                                                                    'size'  => 'sm',
                                                                    'color' => '#555555',
                                                                    'flex'  => 3,
                                                                ),
                                                            1 =>
                                                                array(
                                                                    'type'  => 'text',
                                                                    'text'  => $license,
                                                                    'size'  => 'sm',
                                                                    'color' => '#111111',
                                                                    'align' => 'end',
                                                                    'flex'  => 9,
                                                                ),
                                                        ),
                                                ),
                                        ),
                                ),
                            4 =>
                                array(
                                    'type'   => 'button',
                                    'action' =>
                                        ['type' => 'uri', 'label' => 'รายละเอียด', 'uri' => $link,],
                                    'color'  => '#262262',
                                    'style'  => 'primary',
                                    'margin' => '25px',
                                ),
                        ),
                ),
        ];
        $flexRaw = ['type' => 'flex', 'altText' => "EEC Line's Maintenance Approval Report.", 'contents' => $content];

        $flexBuilder = new LINEBot\MessageBuilder\RawMessageBuilder($flexRaw);


        if ($role === 'technician')
        {
            $this->bot->pushMessage($to, $flexBuilder);
        } else
        {
            return ['to' => $to, 'flex' => $flexRaw, 'flexBuilder' => $flexBuilder];
        }
    }

    public function thaiDate()
    {
        $monthTH = [null, 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];

        $thai_date_return = date("j", time());
        $thai_date_return .= " " . $monthTH[date("n", time())];
        $thai_date_return .= " " . (date("Y", time()) + 543);

        return $thai_date_return;
    }

}

