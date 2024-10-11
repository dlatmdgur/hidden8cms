<?php


namespace App\Http\Controllers;


use App\Exports\ExchangeLogExport;
use App\Model\Account\AccountInfo;
use App\Model\Account\BackupBanList;
use App\Model\CMS\AdminLog;
use App\Model\Game\UserInfo;
use App\Model\Log\ExchangeLog;
use App\Model\Log\ExchnageLimitPass;
use App\Model\Log\LoginLog;
use App\Model\Statistics\Stats;
use App\Model\Statistics\StatsCcu;
use App\Model\Statistics\StatsUsers;
use App\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class StatisticsController extends Controller
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
     * Statistics CurrentConnectedUser.
     *
     * @return Renderable
     */
    public function ccu()
    {
        return view('statistics.ccu');
    }

    /**
     * Statistics CurrentConnectedUser Live.
     *
     * @return Renderable
     */
    public function ccuLive()
    {
        return view('statistics.cculive');
    }


    /**
     * Statistics Sales.
     *
     * @return Renderable
     */
    public function sales()
    {
        return view('statistics.sales');
    }

    /**
     * Statistics Items.
     *
     * @return Renderable
     */
    public function items()
    {
        return view('statistics.items');
    }

    /**
     * Statistics Goods.
     *
     * @return Renderable
     */
    public function users()
    {
        return view('statistics.users');
    }

    /**
     * Statistics Goods.
     *
     * @return Renderable
     */
    public function goodsGame()
    {
        return view('statistics.goodsgame');
    }

    /**
     * Statistics Goods.
     *
     * @return Renderable
     */
    public function goodsUser()
    {
        return view('statistics.goodsuser');
    }

    /**
     * Exchange Logs.
     *
     * @return Renderable
     */
    public function exchange()
    {
        return view('statistics.exchange');
    }

    /**
     * Exchange Users Logs.
     *
     * @param Request $request
     * @return Renderable
     */
    public function exchangeUser(Request $request)
    {
        $userSeq = $request->input('userSeq');
        $data = array('userSeq' => $userSeq);
        return view('statistics.exchangeuser', $data);
    }

    /**
     * Exchange Limit Except Users Logs.
     *
     * @param Request $request
     * @return Renderable
     */
    public function exchangeLimitExcept(Request $request)
    {
        $passList = ExchnageLimitPass::getExchangeLimitPassUsers();
        $data = array('passList' => $passList);
        return view('statistics.exchangelimitexcept', $data);
    }

    /**
     * Statistics Goods.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getDailyLogs(Request $request)
    {
        $this->middleware('gzip');

        $error = true;
        $dailyLogs = null;

        // extra
        $topGoldLogs = null;
        $allExchangeLog = null;

        $status = 404;
        $messages = ['데이터가 없습니다.'];

        $validator = Validator::make($request->input(), array(
            'target' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
            'from' => 'required',
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $target = $request->input('target');
        $platform = $request->input('platform');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // get Logs by Target
        if ($target == "sales") {
            $dailyLogs = Stats::getDailyBillingLog($startDate, $endDate);
        } else if ($target == "items") {
            $dailyLogs = Stats::getDailyBuyItemLog($startDate, $endDate);
        } else if ($target == "exchange") {
            $limit = 30000000;
            $topGoldLogs = ExchangeLog::getTodayTopGoldLog($limit);
            $dailyLogs = ExchangeLog::getDailyExchangeLog($startDate, $endDate);
        } else if ($target == "exchangeUser") {

           $type = $request->input('type');
           $keyword = trim($request->input('keyword'));

           // find user_seq & account info
           if ($type === 'nickname') {
               $accountInfo = AccountInfo::where(DB::raw('BINARY `nickname`'), $keyword)->first();
           } else if ($type === 'userSeq') {
               $accountInfo = AccountInfo::where('user_seq', $keyword)->first();
           } else if ($type === 'email') {
               if ($platform == '1') {
                   $accountInfo = AccountInfo::where('google_email', $keyword)->orwhere('platform_id', $keyword)->first();
               } else {
                   $accountInfo = AccountInfo::where('login_type', $platform)->where('google_email', $keyword)->first();
               }
           }

            if (!is_null($accountInfo)) {
                $dailyLogs = ExchangeLog::getDailyExchangeLogByUser($accountInfo->user_seq, $startDate, $endDate);
                $allExchangeLog = ExchangeLog::getExchangeLogByUser($accountInfo->user_seq, $startDate, $endDate);
            }

        } else if ($target == "users") {
            $dailyLogs = StatsUsers::getDailyLog($startDate, $endDate);
        } else if ($target == "goods") {
            $dailyLogs = [];
            $dailyLogs['games'] = [];
            $dailyLogs['total'] = [];
            $dailyLogs['money'] = [];

            // 라이브서버에만 StatDB가 존재함
            if (env('APP_ENV') == "production") {
                $dailyLogs['money'] = Stats::getDailyMoneyLog($startDate, $endDate);
                $dailyLogs['games'] = Stats::getDailyStatsTotalLog($startDate, $endDate);


                $tmpLog = [
                    'date' => '',
                    'chip_sum_inc' => 0, 'chip_sum_dec' => 0, 'chip_income' => 0,
                    'gold_sum_inc' => 0, 'gold_sum_dec' => 0, 'gold_income' => 0,
                    ];
                foreach ($dailyLogs['games'] as $log) {
                    if ($tmpLog['date'] == '') {
                        $tmpLog['date'] = $log->date;
                    }

                    if ($tmpLog['date'] != $log->date) {
                        // add previous
                        $dailyLogs['total'][] = $tmpLog;

                        // 초기화
                        $tmpLog = [
                            'date' => $log->date,
                            'chip_sum_inc' => 0, 'chip_sum_dec' => 0, 'chip_income' => 0,
                            'gold_sum_inc' => 0, 'gold_sum_dec' => 0, 'gold_income' => 0,
                            ];
                    }

                    if (intval($log->subtype) < 2) {
                        $tmpLog['chip_sum_inc'] += $log->sum_inc;
                        $tmpLog['chip_sum_dec'] += $log->sum_dec;
                        $tmpLog['chip_income'] += $log->income;
                    } else {
                        $tmpLog['gold_sum_inc'] += $log->sum_inc;
                        $tmpLog['gold_sum_dec'] += $log->sum_dec;
                        $tmpLog['gold_income'] += $log->income;
                    }
                }
                $dailyLogs['total'][] = $tmpLog;
            }
        }

        if (!is_null($dailyLogs)) {
            $error = false;
            $status = 200;
            $messages = [];
        }

        return response()->json([
            'error' => $error,
            'dailyLogs' => $dailyLogs,
            'topGoldLogs' => $topGoldLogs,
            'allExchangeLog' => $allExchangeLog,
            'messages' => $messages
        ], $status);
    }

    /**
     * Statistics Goods.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getCcuLogs(Request $request)
    {
        $this->middleware('gzip');

        $error = true;
        $ccuLogs = null;

        $status = 404;
        $messages = ['데이터가 없습니다.'];

        $validator = Validator::make($request->input(), array(
            'target' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
            'minute' => 'required',
            'from' => 'required',
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $target = $request->input('target');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $minute = $request->input('minute');
        if ($minute == "") {
            $period = 1;
        }

        // get Logs by Target
        if (intVal($minute) > 60) {
            $ccuLogs = StatsCcu::getCcuByDaily($startDate, $endDate);
        } else {
            $ccuLogs = StatsCcu::getCcuByMinute($startDate, $endDate, $minute);
        }

        if (!is_null($ccuLogs)) {
            $error = false;
            $status = 200;
            $messages = [];
        }

        return response()->json([
            'error' => $error,
            'ccuLogs' => $ccuLogs,
            'messages' => $messages
        ], $status);
    }

    /**
     * Statistics Exchange Logs.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getExchangeLogs(Request $request)
    {
        $this->middleware('gzip');

        $error = true;
        $dailyLogs = null;

        // extra
        $topGoldLogs = null;
        $allExchangeLog = null;
        $overLimitLogs = null;
        $excelLogs = null;

        // user
        $accountInfo = null;
        $userInfo = null;
        $loginLog = null;
        $banInfo = null;
        $adminLog = null;

        $status = 404;
        $messages = ['데이터가 없습니다.'];

        $validator = Validator::make($request->input(), array(
            'target' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
            'from' => 'required',
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $target = $request->input('target');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        if ($target == "exchange") {

            $limit = 10000000;
            $topGoldLogs = ExchangeLog::getTodayTopGoldLog($limit);

            $highLimit = 2000000;
            $overLimitLogs = $allExchangeLog = ExchangeLog::getExchangeLogOverLimit($highLimit, $startDate, $endDate);

            $dailyLogs = ExchangeLog::getDailyExchangeLog($startDate, $endDate);

            //excel
            $excelLogs = ExchangeLog::getExchangeLogByDate($startDate, $endDate);

        } else if ($target == "exchangeUser") {

            $type = $request->input('type');
            $keyword = trim($request->input('keyword'));

            // find user_seq & account info
            if ($type === 'nickname') {
                $accountInfo = AccountInfo::where(DB::raw('BINARY `nickname`'), $keyword)->first();
            } else if ($type === 'userSeq') {
                $accountInfo = AccountInfo::where('user_seq', $keyword)->first();
            } else if ($type === 'email') {
                $accountInfo = AccountInfo::where('google_email', $keyword)->orwhere('platform_id', $keyword)->first();
            }

            if (!is_null($accountInfo)) {
                // find member by user_seq
                $userInfo = UserInfo::where('user_seq', $accountInfo->user_seq)->first();

                // find ban list
                $banInfo = BackupBanList::where('user_seq', $accountInfo->user_seq)->get();

                // find login log
                $loginLog = LoginLog::where('user_seq', $accountInfo->user_seq)->orderby('log_seq', 'DESC')->first();

                // find admin logs
                $adminLog = AdminLog::where('user_seq', $accountInfo->user_seq)->orderby('id', 'DESC')->first();

                $dailyLogs = ExchangeLog::getDailyExchangeLogByUser($accountInfo->user_seq, $startDate, $endDate);
                $allExchangeLog = ExchangeLog::getExchangeLogByUser($accountInfo->user_seq, $startDate, $endDate);
            }
        }

        if (!is_null($dailyLogs)) {
            $error = false;
            $status = 200;
            $messages = [];
        }

        return response()->json([
            'error' => $error,
            'accountInfo' => $accountInfo,
            'userInfo' => $userInfo,
            'banInfo' => $banInfo,
            'loginLog' => $loginLog,
            'adminLog' => $adminLog,
            'dailyLogs' => $dailyLogs,
            'topGoldLogs' => $topGoldLogs,
            'allExchangeLog' => $allExchangeLog,
            'overLimitLogs' => $overLimitLogs,
            'messages' => $messages
        ], $status);
    }

    public function excelExchangeLogs(Request $request)
    {
        $excelLogs = null;

        $validator = Validator::make($request->input(), array(
            'target' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
            'from' => 'required',
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $exchangeLogs = ExchangeLog::getExchangeLogByDate($startDate, $endDate);

        array_unshift($exchangeLogs, ['Date', 'user_seq', 'nickname', 'type', 'gold_amount', 'coin_amount']);

        //excel
        $excelData = [
            'fileName' => 'ExchangeLog(' . $startDate ."-" . $endDate . ").xlsx",
            'title' => 'ExchangeLog ' . $startDate."-".$endDate,
            'data' => $exchangeLogs
        ];

        $export = new ExchangeLogExport($excelData['data']);

        return Excel::download($export, $excelData['fileName']);

    }

    /**
     * Statistics Search Exchange pass User.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchPassUser(Request $request)
    {
        $this->middleware('gzip');

        $error = true;

        // user
        $accountInfo = null;
        $userInfo = null;
        $loginLog = null;
        $banInfo = null;
        $adminLog = null;

        $status = 404;
        $messages = ['유저가 존재하지 않습니다.'];

        $validator = Validator::make($request->input(), array(
            'type' => 'required',
            'keyword' => 'required',
            'from' => 'required',
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $type = $request->input('type');
        $keyword = trim($request->input('keyword'));

        // find user_seq & account info
        if ($type === 'nickname') {
            $accountInfo = AccountInfo::where(DB::raw('BINARY `nickname`'), $keyword)->first();
        } else if ($type === 'userSeq') {
            $accountInfo = AccountInfo::where('user_seq', $keyword)->first();
        } else if ($type === 'email') {
            $accountInfo = AccountInfo::where('google_email', $keyword)->orwhere('platform_id', $keyword)->first();
        }

        if (!is_null($accountInfo)) {
            // find member by user_seq
            $userInfo = UserInfo::where('user_seq', $accountInfo->user_seq)->first();

            // find ban list
            $banInfo = BackupBanList::where('user_seq', $accountInfo->user_seq)->get();

            // find login log
            $loginLog = LoginLog::where('user_seq', $accountInfo->user_seq)->orderby('log_seq', 'DESC')->first();

            // find admin logs
            $adminLog = AdminLog::where('user_seq', $accountInfo->user_seq)->orderby('id', 'DESC')->first();

            $error = false;
            $status = 200;
            $messages = [];
        }

        return response()->json([
            'error' => $error,
            'accountInfo' => $accountInfo,
            'userInfo' => $userInfo,
            'banInfo' => $banInfo,
            'loginLog' => $loginLog,
            'adminLog' => $adminLog,
            'messages' => $messages
        ], $status);
    }

    /**
     * Statistics add Exchange Limit pass User.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function addPassUser(Request $request)
    {
        //$this->middleware('gzip');

        $error = true;

        // user
        $accountInfo = null;
        $userInfo = null;
        $loginLog = null;
        $banInfo = null;
        $adminLog = null;

        $status = 404;
        $messages = ['유저가 존재하지 않습니다.'];

        $validator = Validator::make($request->input(), array(
            'user_seq' => 'required',
            'from' => 'required',
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $userSeq = trim($request->input('user_seq'));

        // find user_seq & account info
        $accountInfo = AccountInfo::where('user_seq', $userSeq)->first();

        if (!is_null($accountInfo)) {
            // find member by user_seq
            $exists = ExchnageLimitPass::where('user_seq', $accountInfo->user_seq)->exists();

            if ($exists) {
                $error = true;
                $messages = ['이미 등록된 유저입니다.'];
            } else {

                // add
                $adminUser = User::find(Auth::id());

                $passUser = new ExchnageLimitPass();
                $passUser->user_seq = $accountInfo->user_seq;
                $passUser->admin_id = $adminUser->id;
                $passUser->admin_name = $adminUser->name;
                $passUser->created_date = Carbon::now();

                $passUser->save();

                $error = false;
                $status = 200;
                $messages = ['등록되었습니다.'];

            }
        }

        return response()->json([
            'error' => $error,
            'messages' => $messages
        ], $status);
    }
}
