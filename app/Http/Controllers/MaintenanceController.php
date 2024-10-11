<?php

namespace App\Http\Controllers;

use App\Model\Game\RankSchedule;
use App\Model\Tables\Constant;
use App\Model\Tables\ConstantSheetsImport;
use App\Model\Tables\Item;
use App\Model\Tables\ItemImport;
use App\Model\Tables\Level;
use App\Model\Tables\LevelImport;
use App\Model\Tables\Member;
use App\Model\Tables\MemberImport;
use App\Model\Tables\Product;
use App\Model\Tables\ProductImport;
use App\Model\Tables\ProductName;
use App\Model\Tables\ProductNameImport;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class MaintenanceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show ranking.
     *
     * @return Renderable
     */
    public function ranking()
    {
        return view('maintenance.ranking');
    }

    /**
     * get Ranking Schedules.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function rankSchedule(Request $request)
    {
        $this->middleware('gzip');

        $error = true;
        $scheduleList = null;

        $status = 404;
        $messages = ['데이터가 없습니다.'];

        $validator = Validator::make($request->input(), array(
            'from' => 'required',
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $params = [];
        $params['gametype'] = $request->input('gameType');
        $params['subtype'] = $request->input('subType');
        $params['start'] = $request->input('startDate');
        $params['end'] = $request->input('endDate');
        $params['from'] = $request->input('from');

        // find user_seq & account info
        $scheduleList = RankSchedule::getList($params['gametype'], $params['subtype'], $params['start'], $params['end']);
        if (!empty($scheduleList)) {
            $status = 200;
            $error = false;
            $messages = null;
        }

        return response()->json([
            'error' => $error,
            'scheduleList' => $scheduleList,
            'messages' => $messages
        ], $status);
    }

    /**
     * edit, new Ranking Schedules.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function editSchedule(Request $request)
    {
        $this->middleware('gzip');

        $error = true;
        $schedule = null;
        $result = false;

        $status = 404;
        $messages = ['데이터가 없습니다.'];

        $validator = Validator::make($request->input(), array(
            'id' => 'required',
            'gameType' => 'required',
            'subType' => 'required',
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

        $params = [];
        $params['id'] = $request->input('id');
        $params['gametype'] = $request->input('gameType');
        $params['subtype'] = $request->input('subType');
        $params['start'] = $request->input('startDate');
        $params['end'] = $request->input('endDate');
        $params['from'] = $request->input('from');

        // find schedule
        $schedule = RankSchedule::getSchedule($params['id']);
        if (!empty($schedule)) {
            // 진행 중이면 수정 불가
            $now = Carbon::now()->setTimezone('Asia/Seoul');
            $checkStart = Carbon::createFromFormat('Y-m-d H:i:s', $params['start'], 'Asia/Seoul');
            $scheduleStart = Carbon::createFromFormat('Y-m-d H:i:s', $schedule->start, 'Asia/Seoul');
            $scheduleEnd = Carbon::createFromFormat('Y-m-d H:i:s', $schedule->end, 'Asia/Seoul');

            if ($now->diffInMinutes($checkStart, false) < 0) {
                $messages = ['스케줄의 시작시간은 현재시간보다 이전일 수 없습니다.'];
            } else if ($now->diffInMinutes($checkStart, true) <= 60) {
                $messages = ['스케줄의 시작시간은 현재시간 1시간 이내로 수정할 수 없습니다.'];
            } else if ($now->diffInMinutes($scheduleStart, true) <= 60) {
                $messages = ['랭킹전이 진행 중인 스케줄은 수정할 수 없습니다.(시작시간 1시간 전부터)'];
            } else if ($now->diffInMinutes($scheduleEnd, false) <= 0) {
                $messages = ['랭킹전이 진행 중인 스케줄은 수정할 수 없습니다.'];
            } else {
                $result = RankSchedule::updateSchedule($params);
                $messages = ['스케줄이 수정되었습니다.'];
            }
        } else {
            // 신규
            $result = RankSchedule::saveSchedule($params);
            $messages = ['스케줄이 등록되었습니다.'];
        }

        if ($result) {
            $status = 200;
            $error = false;
        } else {
            $messages = is_null($messages) ? ['DB에러가 발생하였습니다.'] : $messages;
        }

        return response()->json([
            'error' => $error,
            'result' => $result,
            'messages' => $messages
        ], $status);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function tables()
    {
        $user = Auth::user();
        if (!$user->hasPermissionTo('maintenance')) {
            return response()->json([
                'error' => true,
                'messages' => 'user has not permission to access',
            ], 400);
        }

        // Constants
        Constant::query()->truncate();
        $constantFile = resource_path('maintenance/ConstantTemplate.xlsx');
        // import to db
        Excel::import(new ConstantSheetsImport, $constantFile);

        // Items
        Item::query()->truncate();
        $itemFile = resource_path('maintenance/GameItemInfoTable.xlsx');
        // import to db
        Excel::import(new ItemImport, $itemFile);

        // Levels
        Level::query()->truncate();
        $levelFile = resource_path('maintenance/LevelUpInfoTable.xlsx');
        // import to db
        Excel::import(new LevelImport, $levelFile);

        // Members
        Member::query()->truncate();
        $memberFile = resource_path('maintenance/MembersBenefitTable.xlsx');
        // import to db
        Excel::import(new MemberImport, $memberFile);

        // Product
        Product::query()->truncate();
        $productFile = resource_path('maintenance/ProductInfoTable.xlsx');
        // import to db
        Excel::import(new ProductImport, $productFile);

        // Product
        ProductName::query()->truncate();
        $productNameFile = resource_path('maintenance/ProductInfoNameTable.xlsx');
        // import to db
        Excel::import(new ProductNameImport, $productNameFile);

        return response()->json([
            'error' => false,
            'messages' => 'resources tables imported!',
        ], 200);
    }
}
