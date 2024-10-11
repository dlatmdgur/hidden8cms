<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Model\Account\AccountInfo;
use App\Model\CMS\AdminLog;
use App\Model\Game\Inventory;
use App\Model\Game\Present;
use App\Model\Game\UserInfo;
use App\Model\Log\UseTicketSeedLog;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class OperationController extends Controller
{
    private $adminUser;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the chipGold send/revoke.
     *
     * @return Renderable
     */
    public function chipGold()
    {
        return view('operations.chipgold');
    }

    /**
     * Edit user's chip gold.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function editChipGold(Request $request)
    {
        $error = true;
        $accountInfo = null;
        $userInfo = null;

        $status = 404;
        $messages = ['회원정보를 찾지 못했습니다.'];

        $validator = Validator::make($request->input(), array(
            'userSeq' => 'required',
            'actionType' => 'required',
            'logType' => 'required',
            'logReason' => 'required',
            'target' => 'required',
            'changeAmount' => 'required',
            'from' => 'required',
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $params = [];
        $params['userSeq'] = $request->input('userSeq');
        $params['actionType'] = $request->input('actionType');
        $params['logType'] = $request->input('logType');
        $params['logReason'] = $request->input('logReason');
        $params['target'] = $request->input('target');
        $params['changeAmount'] = $request->input('changeAmount');
        $params['from'] = $request->input('from');

        $userSeq = $request->input('userSeq');
        $actionType = $request->input('actionType');
        $logType = $request->input('logType');
        $logReason = $request->input('logReason');
        $target = $request->input('target');
        $changeAmount = $request->input('changeAmount');

        $beforeValue = null;
        $afterValue = null;
        // find user_seq & account info
        $accountInfo = AccountInfo::where('user_seq', $userSeq)->first();

        if (!is_null($accountInfo)) {
            if ($accountInfo->user_state === "2") {
                $messages = ['탈퇴 유저는 지급/회수 할 수 없습니다.'];
            } else {

                $error = false;
                $status = 200;
                $messages = null;

                // find member by user_seq
                $userInfo = UserInfo::where('user_seq', $accountInfo->user_seq)->first();
                if ($actionType == "revoke") {
                    $changeAmount = -1 * $changeAmount;
                }

                $beforeValue = [
                    'chip' => $userInfo->chip,
                    'gold' => $userInfo->gold,
                    'safe_chip' => $userInfo->safe_chip,
                    'safe_gold' => $userInfo->safe_gold,
                ];

                if ($target == "chip") {
                    $userInfo->chip = $userInfo->chip + $changeAmount;
                } else if ($target == "safe_chip") {
                    $userInfo->safe_chip = $userInfo->safe_chip + $changeAmount;
                }
                else if ($target == "gold") {
                    $userInfo->gold = $userInfo->gold + $changeAmount;
                } else if ($target == "safe_gold") {
                    $userInfo->safe_gold = $userInfo->safe_gold + $changeAmount;
                }
                $userInfo->save();

                $afterValue = [
                    'chip' => $userInfo->chip,
                    'gold' => $userInfo->gold,
                    'safe_chip' => $userInfo->safe_chip,
                    'safe_gold' => $userInfo->safe_gold,
                ];

                // admin Log
                $this->adminUser = User::find(Auth::id());
                AdminLog::create([
                    'type' => 'chipgold_edit',
                    'menu' => 'operation',
                    'action' => 'editChipGold',
                    'log_type' => $logType,
                    'params' => json_encode($params),
                    'reason' => Helper::adminLogType($logType),
                    'extra' => $logReason,
                    'user_seq' => $accountInfo->user_seq,
                    'nickname' => $accountInfo->nickname,
                    'before_value' => json_encode($beforeValue),
                    'after_value' => json_encode($afterValue),
                    'before_state' => $accountInfo->user_state,
                    'after_state' => $accountInfo->user_state,
                    'admin_id' => $this->adminUser->id,
                    'admin_name' => $this->adminUser->name,
                    'created_at' => DB::raw('now()'),
                    'updated_at' => DB::raw('now()'),
                ]);

                $messages = ['머니 지급/회수가 완료되었습니다.'];
            }
        }

        return response()->json([
            'error' => $error,
            'accountInfo' => $accountInfo,
            'userInfo' => $userInfo,
            'messages' => $messages
        ], $status);
    }

    /**
     * Show the gem send/revoke.
     *
     * @return Renderable
     */
    public function gem()
    {
        return view('operations.gem');
    }

    /**
     * Edit user's Gem, Event Gem.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function editGem(Request $request)
    {
        $error = true;
        $accountInfo = null;
        $userInfo = null;

        $status = 404;
        $messages = ['회원정보를 찾지 못했습니다.'];

        $validator = Validator::make($request->input(), array(
            'userSeq' => 'required',
            'actionType' => 'required',
            'logType' => 'required',
            'logReason' => 'required',
            'target' => 'required',
            'changeAmount' => 'required',
            'from' => 'required',
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $params = [];
        $params['userSeq'] = $request->input('userSeq');
        $params['actionType'] = $request->input('actionType');
        $params['logType'] = $request->input('logType');
        $params['logReason'] = $request->input('logReason');
        $params['target'] = $request->input('target');
        $params['changeAmount'] = $request->input('changeAmount');
        $params['from'] = $request->input('from');

        $userSeq = $request->input('userSeq');
        $actionType = $request->input('actionType');
        $logType = $request->input('logType');
        $logReason = $request->input('logReason');
        $target = $request->input('target');
        $changeAmount = $request->input('changeAmount');

        $beforeValue = null;
        $afterValue = null;
        // find user_seq & account info
        $accountInfo = AccountInfo::where('user_seq', $userSeq)->first();

        if (!is_null($accountInfo)) {
            if ($accountInfo->user_state === "2") {
                $messages = ['탈퇴 유저는 지급/회수 할 수 없습니다.'];
            } else {

                $error = false;
                $status = 200;
                $messages = null;

                // find member by user_seq
                $userInfo = UserInfo::where('user_seq', $accountInfo->user_seq)->first();
                if ($actionType == "revoke") {
                    $changeAmount = -1 * $changeAmount;
                }

                $beforeValue = [
                    'gem' => $userInfo->gem,
                    'event_gem' => $userInfo->gem_event,
                ];

                if ($target == "gem") {
                    $userInfo->gem = $userInfo->gem + $changeAmount;
                } else if ($target == "event_gem") {
                    $userInfo->gem_event = $userInfo->gem_event + $changeAmount;
                }
                $userInfo->save();

                $afterValue = [
                    'gem' => $userInfo->gem,
                    'event_gem' => $userInfo->gem_event,
                ];

                // admin Log
                $this->adminUser = User::find(Auth::id());
                AdminLog::create([
                    'type' => 'gem_edit',
                    'menu' => 'operation',
                    'action' => 'editGem',
                    'log_type' => $logType,
                    'params' => json_encode($params),
                    'reason' => Helper::adminLogType($logType),
                    'extra' => $logReason,
                    'user_seq' => $accountInfo->user_seq,
                    'nickname' => $accountInfo->nickname,
                    'before_value' => json_encode($beforeValue),
                    'after_value' => json_encode($afterValue),
                    'before_state' => $accountInfo->user_state,
                    'after_state' => $accountInfo->user_state,
                    'admin_id' => $this->adminUser->id,
                    'admin_name' => $this->adminUser->name,
                    'created_at' => DB::raw('now()'),
                    'updated_at' => DB::raw('now()'),
                ]);

                $messages = ['보석 지급/회수가 완료되었습니다.'];
            }
        }

        return response()->json([
            'error' => $error,
            'accountInfo' => $accountInfo,
            'userInfo' => $userInfo,
            'messages' => $messages
        ], $status);
    }


    /**
     * Show the posts item send/revoke.
     *
     * @return Renderable
     */
    public function posts()
    {
        return view('operations.posts');
    }

    /**
     * Give / Revoke user's Presents
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function editPresent(Request $request)
    {
        $error = true;
        $accountInfo = null;
        $userInfo = null;

        $status = 404;
        $messages = ['회원정보를 찾지 못했습니다.'];

        $validator = Validator::make($request->input(), array(
            'userSeq' => 'required',
            'actionType' => 'required',
            'logType' => 'required',
            'logReason' => 'required',
            'presentType' => 'required',
            'changeAmount' => 'required',
            'presentSeq' => 'required',
            'from' => 'required',
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $params = [];
        $params['userSeq'] = $request->input('userSeq');
        $params['actionType'] = $request->input('actionType');
        $params['logType'] = $request->input('logType');
        $params['logReason'] = $request->input('logReason');
        $params['presentType'] = $request->input('presentType');
        $params['changeAmount'] = $request->input('changeAmount');
        $params['presentSeq'] = $request->input('presentSeq');
        $params['from'] = $request->input('from');

        $userSeq = $request->input('userSeq');
        $actionType = $request->input('actionType');
        $logType = $request->input('logType');
        $logReason = $request->input('logReason');
        $presentType = $request->input('presentType');
        $changeAmount = $request->input('changeAmount');
        $presentSeq = $request->input('presentSeq');

        $beforeValue = null;
        $afterValue = null;
        // find user_seq & account info
        $accountInfo = AccountInfo::where('user_seq', $userSeq)->first();

        if (!is_null($accountInfo)) {
//            if ($accountInfo->user_state != "3") {
//                $messages = ['유저의 상태를 먼저 변경하세요.'];
//            } else {

            $error = false;
            $status = 200;
            $messages = null;

            // find member by user_seq
            $userInfo = UserInfo::where('user_seq', $accountInfo->user_seq)->first();
            $beforeValue = [
                'actionType' => $actionType,
                'unReadPresentCount' => Present::getUnreadCount($accountInfo->user_seq),
            ];
            if ($actionType == "revoke") {
                // delete from present
                // Present::where('present_seq', $presentSeq)->delete();
                $present = Present::where('present_seq', $presentSeq)->first();
                $params['presentType'] = $present->item_id;
                $params['changeAmount'] = $present->item_ea;

                Present::where('present_seq', $presentSeq)->delete();

            } else if ($actionType == "give") {
                $present = new Present;
                $present->user_seq = $accountInfo->user_seq;
                $present->item_id = $presentType;
                $present->item_ea = $changeAmount;
                $present->sender_seq = -101;            // todo :: remove hard code
                $present->period_time = 86400;         // todo :: remove hard code
                $present->update_date = Carbon::now();
                $present->is_read = 0;
                $present->save();
            }

            $afterValue = [
                'actionType' => $actionType,
                'unReadPresentCount' => Present::getUnreadCount($userInfo->user_seq),
            ];

            // admin Log
            $this->adminUser = User::find(Auth::id());
            AdminLog::create([
                'type' => 'present_edit',
                'menu' => 'operation',
                'action' => 'editPresent',
                'log_type' => $logType,
                'params' => json_encode($params),
                'reason' => Helper::adminLogType($logType),
                'extra' => $logReason,
                'user_seq' => $accountInfo->user_seq,
                'nickname' => $accountInfo->nickname,
                'before_value' => json_encode($beforeValue),
                'after_value' => json_encode($afterValue),
                'before_state' => $accountInfo->user_state,
                'after_state' => $accountInfo->user_state,
                'admin_id' => $this->adminUser->id,
                'admin_name' => $this->adminUser->name,
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()'),
            ]);

            $messages = ['가방 지급/회수가 완료되었습니다.'];
//            }
        }

        return response()->json([
            'error' => $error,
            'accountInfo' => $accountInfo,
            'userInfo' => $userInfo,
            'messages' => $messages
        ], $status);
    }

    /**
     * Massive Send posts.
     *
     * @return Renderable
     */
    public function send()
    {
        $template = link_to_asset('file/sendTemplate.xlsx', 'Excel Template', $attributes = array(), null);
        return view('operations.send', compact('template'));
    }

    /**
     * Send Massive Presents
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendMassive(Request $request)
    {
        $error = true;
        $accountInfo = null;
        $userInfo = null;

        $status = 404;
        $messages = ['회원정보를 찾지 못했습니다.'];

        $validator = Validator::make($request->input(), array(
            'userJson' => 'required',
            'actionType' => 'required',
            'logType' => 'required',
            'logReason' => 'required',
            'presentType' => 'required',
            'changeAmount' => 'required',
            'presentSeq' => 'required',
            'from' => 'required',
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $params = [];
        $params['userJson'] = $request->input('userJson');
        $params['actionType'] = $request->input('actionType');
        $params['logType'] = $request->input('logType');
        $params['logReason'] = $request->input('logReason');
        $params['presentType'] = $request->input('presentType');
        $params['changeAmount'] = $request->input('changeAmount');
        $params['presentSeq'] = $request->input('presentSeq');
        $params['from'] = $request->input('from');

        $userJson = $request->input('userJson');
        $logType = $request->input('logType');
        $logReason = $request->input('logReason');
        $presentType = $request->input('presentType');
        $changeAmount = $request->input('changeAmount');

        $userArray = json_decode($userJson, true);

        $beforeValue = null;
        $afterValue = null;

        if (!empty($userArray)) {
            $beforeValue = [
                'startTime' => Carbon::now(),
                'sendUserCount' => count($userArray),
            ];

            $error = false;
            $status = 200;
            $messages = null;

            $nicknames = [];
            foreach ($userArray as $user) {
                if (isset($user['user_seq'])) {
                    break;
                } else {
                    $nicknames[] = $user['nickname'];
                }
            }

            if (!empty($nicknames)) {
                $userArray = UserInfo::getUsersByNickname(implode(',', $nicknames));
            }

            $insertData = [];
            foreach ($userArray as $user) {
                $data = [
                    'user_seq' => is_array($user) ? $user['user_seq'] : $user->user_seq,
                    'item_id' => $presentType,
                    'item_ea' => $changeAmount,
                    'sender_seq' => -101,
                    'period_time' => 259200,
                    'update_date' => Carbon::now(),
                    'is_read' => 0
                ];
                $insertData[] = $data;
            }

            // send
            $result = Present::multiSendPresents($insertData);

            $afterValue = [
                'endTime' => Carbon::now(),
                'sendUserCount' => count($insertData),
                'result' => $result,
            ];

            // admin Log
            $this->adminUser = User::find(Auth::id());
            AdminLog::create([
                'type' => 'send_massive',
                'menu' => 'operation',
                'action' => 'send',
                'log_type' => $logType,
                'params' => json_encode($params),
                'reason' => Helper::adminLogType($logType),
                'extra' => $logReason,
                'user_seq' => -1,
                'nickname' => 'count: ' . count($insertData),
                'before_value' => json_encode($beforeValue),
                'after_value' => json_encode($afterValue),
                'before_state' => 0,
                'after_state' => 0,
                'admin_id' => $this->adminUser->id,
                'admin_name' => $this->adminUser->name,
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()'),
            ]);

            $messages = ['대량발송이 완료되었습니다.'];

        }

        return response()->json([
            'error' => $error,
            'messages' => $messages
        ], $status);
    }

    /**
     * Modify Deck's Effect Dates, Usability.
     *
     * @return Renderable
     */
    public function effect()
    {
        return view('operations.effect');
    }

    /**
     * Give / Revoke user's Presents
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function editEffect(Request $request)
    {
        $error = true;
        $accountInfo = null;
        $userInfo = null;

        $status = 404;
        $messages = ['회원정보를 찾지 못했습니다.'];

        $validator = Validator::make($request->input(), array(
            'userSeq' => 'required',
            'actionType' => 'required',
            'logType' => 'required',
            'logReason' => 'required',
            'target' => 'required',
            'invenSeq' => 'required',
            'itemId' => 'required',
            'membersType' => 'required',
            'changeDate' => 'required',
            'from' => 'required',
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $params = [];
        $params['userSeq'] = $request->input('userSeq');
        $params['actionType'] = $request->input('actionType');
        $params['logType'] = $request->input('logType');
        $params['logReason'] = $request->input('logReason');
        $params['target'] = $request->input('target');
        $params['invenSeq'] = $request->input('invenSeq');
        $params['itemId'] = $request->input('itemId');
        $params['membersType'] = $request->input('membersType');
        $params['changeDate'] = $request->input('changeDate');
        $params['from'] = $request->input('from');

        $userSeq = $request->input('userSeq');
        $actionType = $request->input('actionType');
        $logType = $request->input('logType');
        $logReason = $request->input('logReason');
        $target = $request->input('target');
        $invenSeq = $request->input('invenSeq');
        $changeDate = $request->input('changeDate');

        $beforeValue = null;
        $afterValue = null;
        // find user_seq & account info
        $accountInfo = AccountInfo::where('user_seq', $userSeq)->first();

        if (!is_null($accountInfo)) {
            if ($accountInfo->user_state === "2 ") {
                $messages = ['유저의 상태를 먼저 변경하세요.'];
            } else {

                $error = false;
                $status = 200;
                $messages = null;

                // find member by user_seq
                $userInfo = UserInfo::where('user_seq', $accountInfo->user_seq)->first();
                $beforeValue = [
                    'actionType' => $actionType,
                    'target' => $target,
                    'invenSeq' => $invenSeq,
                    'period' => '',
                    'endDate' => '',
                ];
                $afterValue = [
                    'actionType' => $actionType,
                    'target' => $target,
                    'invenSeq' => $invenSeq,
                    'period' => '',
                    'endDate' => '',
                ];

                if ($target == "item") {
                    $item = Inventory::where('inven_seq', $invenSeq)->first();
                    $beforeValue['period'] = $item->period_time;
                    $beforeValue['endDate'] = Helper::getDate($item->update_date, $item->period_time);

                    // change period by $changeDate
                    $item->period_time = Helper::getPeriod($item->update_date, $changeDate);
                    $item->save();

                    $afterValue['period'] = $item->period_time;
                    $afterValue['endDate'] = Helper::getDate($item->update_date, $item->period_time);

                } else if ($target == "members") {
                    $beforeValue['period'] = $userInfo->members_period;
                    $beforeValue['endDate'] = Helper::getDate($userInfo->members_start_date, $userInfo->members_period);

                    // change period by $changeDate
                    $userInfo->members_period = Helper::getPeriod($userInfo->members_start_date, $changeDate);
                    $userInfo->save();

                    $afterValue['period'] = $userInfo->members_period;
                    $afterValue['endDate'] = Helper::getDate($userInfo->members_start_date, $userInfo->members_period);
                }

                // admin Log
                $this->adminUser = User::find(Auth::id());
                AdminLog::create([
                    'type' => 'effect_edit',
                    'menu' => 'operation',
                    'action' => 'editEffect',
                    'log_type' => $logType,
                    'params' => json_encode($params),
                    'reason' => Helper::adminLogType($logType),
                    'extra' => $logReason,
                    'user_seq' => $accountInfo->user_seq,
                    'nickname' => $accountInfo->nickname,
                    'before_value' => json_encode($beforeValue),
                    'after_value' => json_encode($afterValue),
                    'before_state' => $accountInfo->user_state,
                    'after_state' => $accountInfo->user_state,
                    'admin_id' => $this->adminUser->id,
                    'admin_name' => $this->adminUser->name,
                    'created_at' => DB::raw('now()'),
                    'updated_at' => DB::raw('now()'),
                ]);

                $messages = ['효력 변경이 완료되었습니다.'];
            }
        }

        return response()->json([
            'error' => $error,
            'accountInfo' => $accountInfo,
            'userInfo' => $userInfo,
            'messages' => $messages
        ], $status);
    }

    /**
     * upload excel file ans response json list.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function excelUpload(Request $request)
    {
        $error = true;
        $accountInfo = null;
        $userInfo = null;

        $status = 404;
        $messages = ['엑셀파일의 데이터를 읽지 못했습니다.'];

        $validator = Validator::make($request->all(), array(
            'excel_file' => 'required|max:50000|mimes:xls,xlsx,csv',
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        // $path = $request->file('excel_file')->getRealPath();
        $readData = Excel::toArray(null, $request->file('excel_file'));
        $resultSet = null;
        $excelData = $readData[0];
        if (count($excelData[0]) > 0) {
            foreach ($excelData as $data) {
                if ($data[0] == "user_seq") {
                    continue;
                }
                if (isset($data[0]) && $data[0] == "nickname") {
                    continue;
                }
                if (isset($data[1]) && $data[1] == "nickname") {
                    continue;
                }
                $dataSet = [];
                if (isset($excelData[0][0])) {
                    $dataSet[$excelData[0][0]] = $data[0];
                }
                if (isset($excelData[0][1])) {
                    $dataSet[$excelData[0][1]] = $data[1];
                }
                if (!empty($dataSet)) {
                    $resultSet[] = $dataSet;
                }
            }

            $error = false;
            $status = 200;
            $messages = ['엑셀 데이터 읽기 완료'];
        }

        return response()->json([
            'error' => $error,
            'resultSet' => $resultSet,
            'messages' => $messages
        ], $status);
    }

    /**
     * 티켓이벤트 수정
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function ticketSeed(Request $request)
    {
        $type = $request->input('search_type');
        $platform = $request->input('login_type');
        $keyword = $request->input('keyword');

        $accountInfo = null;
        if($type === 'nickname') {
            $accountInfo = AccountInfo::where(DB::raw('BINARY `nickname`'), $keyword)->first();
        } elseif($type === 'userSeq') {
            $accountInfo = AccountInfo::find($keyword);
        } elseif($type === 'email') {
            $accountInfo = AccountInfo::where('login_type', $platform)->where('account', $keyword)->first();
        }

        $tickets = [];
        $numberStart = 0;
        if($accountInfo) {
            $builder = Inventory::where('user_seq', $accountInfo->user_seq)
                ->whereIn('item_id', Helper::getTicketSeedItemId())
                ->where('is_delete', 0)->where('is_use', 0)
                ->select('inven_seq', 'item_id', 'item_ea', 'update_date')
                ->orderBy('inven_seq', 'DESC');

            $numberStart = $builder->count();
            $tickets = $builder->get();

            foreach($tickets as $row) {
                $row->item_name = Helper::getTicketSeedItemName(intval($row->item_id));
            }
        }

        $data = [
            'numberStart' => $numberStart,
            'tickets' => $tickets,
            'search' => [
                'search_type' => $type,
                'login_type' => $platform,
                'keyword' => $keyword,
            ],
        ];

        return view('operations.ticketseed', $data);
    }

    /**
     * 티켓이벤트 삭제
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ticketSeedDelete(Request $request)
    {
        $target = $request->input('target');
        $invenSeq = $request->input('seq');

        $output = ['error' => true, 'messages' => '파라미터 오류'];
        $ticket = Inventory::find($invenSeq);
        if(!$ticket) response()->json($output, 200, [], JSON_UNESCAPED_UNICODE);
        if(!in_array($ticket->item_id, Helper::getTicketSeedItemId())) response()->json($output, 200, [], JSON_UNESCAPED_UNICODE);
        if($ticket->item_ea == 0) {
            $output['messages'] = '티켓이벤트 수량 오류';
            response()->json($output, 200, [], JSON_UNESCAPED_UNICODE);
        }

        $beforeValue = $ticket->toArray();
        $ticket->item_ea = intval($ticket->item_ea);
        if($target == 'all') {
            $amount = $ticket->item_ea;
            $ticket->item_ea = 0;
        } else {
            $amount = 1;
            $ticket->item_ea--;
        }
        if($ticket->item_ea === 0) $ticket->is_delete = 1;
        $afterValue = $ticket->toArray();
        $ticket->save();

        $accountInfo = AccountInfo::find($ticket->user_seq);
        $params = ['target' => $target, 'inven_seq' => $invenSeq];

        //game log
        UseTicketSeedLog::create([
            'user_seq' => $accountInfo->user_seq,
            'inven_seq' => $ticket->inven_seq,
            'item_id' => $ticket->item_id,
            'before_count' => $beforeValue['item_ea'],
            'use_count' => $amount,
            'memo' => 'cms delete',
            'log_date' => Carbon::now(),
        ]);

        // admin Log
        $this->adminUser = User::find(Auth::id());
        AdminLog::create([
            'type' => 'seed_ticket_delete',
            'menu' => 'operation',
            'action' => 'delete' . ucfirst($target),
            'log_type' => 'admin',
            'params' => json_encode($params),
            'reason' => Helper::adminLogType('admin'),
            'extra' => '운영자 처리',
            'user_seq' => $accountInfo->user_seq,
            'nickname' => $accountInfo->nickname,
            'before_value' => json_encode($beforeValue),
            'after_value' => json_encode($afterValue),
            'before_state' => $accountInfo->user_state,
            'after_state' => $accountInfo->user_state,
            'admin_id' => $this->adminUser->id,
            'admin_name' => $this->adminUser->name,
            'created_at' => DB::raw('now()'),
            'updated_at' => DB::raw('now()'),
        ]);

        $output['error'] = false;
        $output['messages'] = 'Success';
        return response()->json($output, 200, [], JSON_UNESCAPED_UNICODE);
    }

}