<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Model\Account\AccountInfo;
use App\Model\Account\BackupBanList;
use App\Model\Account\Monitor;
use App\Model\CMS\AdminLog;
use App\Model\Game\UserInfo;
use App\Model\Monitor\IpUser;
use App\User;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class MonitoringController extends Controller
{

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
     * Search Abusing User.
     *
     * @return Renderable
     */
    public function abuse()
    {
        return view('monitor.abuse');
    }

    /**
     * edit Mass User's state.
     *
     * @return Renderable
     */
    public function ban()
    {
        $template = link_to_asset('file/sendTemplate.xlsx', 'Excel Template', $attributes = array(), null);
        return view('monitor.ban', compact('template'));
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
        $messages = ['엑셀파일의 데이터를 읽지 못했습니다..'];

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
     * force Close & edit massive User for Ajax Call.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function editMassive(Request $request)
    {
        $error = true;
        $accountInfo = null;
        $adminLog = null;
        $status = 404;
        $messages = ['회원정보를 찾지 못했습니다.'];

        $validator = Validator::make($request->input(), array(
            'userJson' => 'required',
            'userState' => 'required',
            'actionType' => 'required',
            'logType' => 'required',
            'logReason' => 'required',
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
        $params['userState'] = $request->input('userState');
        $params['actionType'] = $request->input('actionType');
        $params['logType'] = $request->input('logType');
        $params['logReason'] = $request->input('logReason');
        $params['from'] = $request->input('from');

        $userJson = $request->input('userJson');
        $logType = $request->input('logType');
        $logReason = $request->input('logReason');

        $userArray = json_decode($userJson, true);

        $beforeValue = null;
        $afterValue = null;

        if (!empty($userArray)) {
            $beforeValue = [
                'startTime' => Carbon::now(),
                'banUserCount' => count($userArray),
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

            $result = true;
            if ($params['userState'] == 1) {
                // 상태 변경
                try {
                    foreach ($userArray as $user) {
                        $accountInfo = AccountInfo::where('user_seq', $user['user_seq'])->first();
                        if (is_null($accountInfo)) {
                            continue;
                        }

                        $beforeState = $accountInfo->user_state;
                        $accountInfo->user_state = $params['userState'];
                        $accountInfo->update_date = Carbon::now();
                        $accountInfo->save();

                        // admin Log
                        $adminUser = User::find(Auth::id());
                        AdminLog::create([
                            'type' => 'ban_massive',
                            'menu' => 'member',
                            'action' => 'ban',
                            'log_type' => $logType,
                            'params' => json_encode($params),
                            'reason' => Helper::adminLogType($logType),
                            'extra' => $logReason . ' 이전 상태 : ' . $beforeState,
                            'user_seq' => $accountInfo->user_seq,
                            'nickname' => $accountInfo->nickname,
                            'before_value' => json_encode($beforeValue),
                            'after_value' => json_encode($afterValue),
                            'before_state' => $beforeState,
                            'after_state' => $params['userState'],
                            'admin_id' => $adminUser->id,
                            'admin_name' => $adminUser->name,
                            'created_at' => DB::raw('now()'),
                            'updated_at' => DB::raw('now()'),
                        ]);

                        usleep(12500);
                    }
                } catch (Exception $e) {
                    $result = false;
                    echo $e;
                }
            } else {
                $channel = ($params['userState'] == 3) ? "ban" : "ban_cs";
                $banType = ($params['userState'] == 3) ? "정지" : "CS처리중";
                try {
                    foreach ($userArray as $user) {
                        // backup ban list
                        $userInfo = UserInfo::getUserForBan($user['user_seq']);

                        if (is_null($userInfo)) {
                            continue;
                        }

                        // redis pub
                        Redis::publish($channel, $user['user_seq']);

                        $beforeState = $userInfo->user_state;
                        BackupBanList::updateOrCreate(
                            [
                                'user_seq' => $userInfo->user_seq,
                            ],
                            [
                                'account' => $userInfo->account,
                                'nickname' => $userInfo->nickname,
                                'chip' => $userInfo->chip,
                                'safe_chip' => $userInfo->safe_chip,
                                'gold' => $userInfo->gold,
                                'safe_gold' => $userInfo->safe_gold,
                                'gem' => $userInfo->gem,
                                'event_gem' => $userInfo->gem_event,
                                'comment' => '일괄변경 ' . $banType,
                                'date' => DB::raw('now()'),
                            ]
                        );

                        // admin Log
                        $adminUser = User::find(Auth::id());
                        AdminLog::create([
                            'type' => 'ban_massive',
                            'menu' => 'member',
                            'action' => 'ban',
                            'log_type' => $logType,
                            'params' => json_encode($params),
                            'reason' => Helper::adminLogType($logType),
                            'extra' => $logReason . ' 이전 상태 : ' . $beforeState,
                            'user_seq' => $userInfo->user_seq,
                            'nickname' => $userInfo->nickname,
                            'before_value' => json_encode($beforeValue),
                            'after_value' => json_encode($afterValue),
                            'before_state' => $beforeState,
                            'after_state' => $params['userState'],
                            'admin_id' => $adminUser->id,
                            'admin_name' => $adminUser->name,
                            'created_at' => DB::raw('now()'),
                            'updated_at' => DB::raw('now()'),
                        ]);

                        usleep(12500);
                    }
                } catch (Exception $e) {
                    $result = false;
                    echo $e;
                }
            }

            $afterValue = [
                'endTime' => Carbon::now(),
                'banUserCount' => count($userArray),
                'result' => $result,
            ];

            // admin Log
            $adminUser = User::find(Auth::id());
            AdminLog::create([
                'type' => 'ban_massive',
                'menu' => 'member',
                'action' => 'ban',
                'log_type' => $logType,
                'params' => json_encode($params),
                'reason' => Helper::adminLogType($logType),
                'extra' => $logReason,
                'user_seq' => -1,
                'nickname' => 'count: ' . count($userArray),
                'before_value' => json_encode($beforeValue),
                'after_value' => json_encode($afterValue),
                'before_state' => 0,
                'after_state' => 0,
                'admin_id' => $adminUser->id,
                'admin_name' => $adminUser->name,
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()'),
            ]);

            $messages = ['일괄변경이 완료되었습니다.'];
        }

        return response()->json([
            'error' => $error,
            'messages' => $messages
        ], $status);
    }

    /**
     * Search Users by IP.
     *
     * @return Renderable
     */
    public function ipUsers()
    {
        return view('monitor.ipusers');
    }

    /**
     * search User list by IP.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchIp(Request $request)
    {
        $error = true;
        $ipList = null;

        $status = 404;
        $messages = ['검색결과가 없습니다.'];

        $validator = Validator::make($request->all(), array(
            'startDate' => 'required',
            'endDate' => 'required',
            'from' => 'required'
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('startDate') . ' 00:00:00');
        $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('endDate') . ' 23:59:59');
        $from = $request->input('from');

        if ($from == "ipUsers") {
            $error = false;
            $status = 200;
            $messages = null;

            $ipList = IpUser::getIps($startDate, $endDate);
        }

        return response()->json([
            'error' => $error,
            'ipList' => $ipList,
            'messages' => $messages
        ], $status);
    }

    /**
     * search User list by IP.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchIpUsers(Request $request)
    {
        $this->middleware('gzip');

        $error = true;
        $ipUserList = null;

        $status = 404;
        $messages = ['검색결과가 없습니다.'];

        $validator = Validator::make($request->all(), array(
            'ip' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
            'from' => 'required'
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $ip = $request->input('ip');
        $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('startDate') . ' 00:00:00');
        $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('endDate') . ' 23:59:59');
        $from = $request->input('from');

        if ($from == "ipUsers") {
            $error = false;
            $status = 200;
            $messages = null;

            $ipUserList = IpUser::getIpUsers($ip, $startDate, $endDate);
        }
        return response()->json([
            'error' => $error,
            'ipUserList' => $ipUserList,
            'messages' => $messages
        ], $status);
    }


    /**
     * Group User List.
     *
     * @return Renderable
     */
    public function groupUsers(Request $request)
    {
        $groups = Monitor::getGroup();

        $group = $request->input("group");
        $startDate = $request->input("startDate");
        $endDate = $request->input("endDate");

        return view('monitor.groupusers', ['groups' => $groups, 'group' => $group, 'endDate' => $startDate, 'endDate' => $endDate]);
    }

    /**
     * Group Users Login, Game Logs.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function groupLogs(Request $request)
    {
        $this->middleware('gzip');

        $error = true;
        $groupUsers = null;
        $groupHoldem = null;
        $groupBadugi = null;

        $status = 404;
        $messages = ['검색결과가 없습니다.'];

        $validator = Validator::make($request->all(), array(
            'group' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
            'from' => 'required'
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $group = $request->input('group');
        $orderBy = $request->input('orderBy');
        $sort = $request->input('sort');
        $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('startDate') . ' 00:00:00');
        $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('endDate') . ' 23:59:59');
        $from = $request->input('from');

        if ($from == "groupUsers") {
            $error = false;
            $status = 200;
            $messages = null;

            $groupUsers = Monitor::groupLoginLog($group, $startDate, $endDate, $orderBy, $sort);
            $groupHoldem = Monitor::groupHoldemLog($group, $startDate, $endDate);
            $groupBadugi = Monitor::groupBadugiLog($group, $startDate, $endDate);
        }

        return response()->json([
            'error' => $error,
            'groupUsers' => $groupUsers,
            'groupHoldem' => $groupHoldem,
            'groupBadugi' => $groupBadugi,
            'messages' => $messages
        ], $status);
    }

}
