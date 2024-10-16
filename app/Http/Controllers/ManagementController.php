<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Model\Account\AccountInfo;
use App\Model\CMS\AdminLog;
use App\Model\CMS\CsFaq;
use App\Model\CMS\CsNotice;
use App\Model\CMS\ClientVersion;
use App\Model\CMS\RollingNotice;
use App\Model\CMS\ServerVersion;
use App\Model\CMS\Tournament;
use App\Model\CMS\Whitelist;
use App\User;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class ManagementController extends Controller
{
    private $adminUser;
    private $today;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->today = Carbon::now()->format('Y-m-d');
    }

    /**
     * File upload from editor
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request)
    {
        $error = true;
        $fileUrl = null;
        $basePath = 'public/upload/';
        $publicPath = '/storage/upload/';

        $status = 404;
        $messages = ['파일업로드에 실패하였습니다.'];

        $validator = Validator::make($request->all(), [
            'file' => 'required|max:4096|mimes:jpg,jpeg,png,bmp,tiff',
        ], $messages = [
            'mimes' => 'Please insert image only',
            'max' => 'Image should be less than 4 MB'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $image = $request->file('file');
        $extension = $image->getClientOriginalExtension();
        $result = Storage::disk('local')->put($basePath . date('Ymd') . '/' . $image->getFilename() . '.' . $extension, File::get($image));

        if ($result) {
            $error = false;
            $status = 200;
            $messages = ['파일업로드에 성공하였습니다.'];
            $fileUrl = $publicPath . date('Ymd') . '/' . $image->getFilename() . '.' . $extension;
        }

        return response()->json([
            'error' => $error,
            'fileUrl' => $fileUrl,
            'messages' => $messages
        ], $status);
    }

    /**
     * Preview Notice
     *
     * @return JsonResponse
     */
    public function previewNotice()
    {
        $error = true;
        $status = 422;
        $fileUrl = null;
        $messages = ['미리보기 생성에 실패했습니다.'];

        $noticeList = CsNotice::getPreviewList();

        if (!empty($noticeList)) {
            // write file
            $noticeFile = '/preview/notice.json';
            Storage::disk('public')->put($noticeFile, json_encode($noticeList));

            $error = false;
            $status = 200;
            $fileUrl = '/storage' . $noticeFile;
            $messages = ['미리보기가 생성되었습니다.'];
        }

        return response()->json([
            'error' => $error,
            'fileUrl' => $fileUrl,
            'messages' => $messages
        ], $status);
    }

    /**
     * Publish Notice
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function publishNotice(Request $request)
    {
        $error = true;
        $status = 422;
        $fileUrl = null;
        $messages = ['배포에 실패했습니다.'];

        $noticeList = CsNotice::getPreviewList();

        if (!empty($noticeList)) {
            // write file
            $noticeFile = '/publish/notice.json';
            Storage::disk('public')->put($noticeFile, json_encode($noticeList));

            $error = false;
            $status = 200;
            $fileUrl = '/storage' . $noticeFile;
            $messages = ['배포에 성공하였습니다.'];
        }

        return response()->json([
            'error' => $error,
            'fileUrl' => $fileUrl,
            'messages' => $messages
        ], $status);
    }


    /**
     * Preview FAQ
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function previewFaq(Request $request)
    {
        $error = true;
        $status = 422;
        $fileUrl = null;
        $messages = ['미리보기 생성에 실패했습니다.'];

        $faqList = CsFaq::getPreviewList();

        if (!empty($faqList)) {
            // write file
            $faqFile = '/preview/faq.json';
            Storage::disk('public')->put($faqFile, json_encode($faqList));

            $error = false;
            $status = 200;
            $fileUrl = '/storage' . $faqFile;
            $messages = ['미리보기가 생성되었습니다.'];
        }

        return response()->json([
            'error' => $error,
            'fileUrl' => $fileUrl,
            'messages' => $messages
        ], $status);
    }

    /**
     * Publish FAQ
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function publishFaq(Request $request)
    {
        $error = true;
        $status = 422;
        $fileUrl = null;
        $messages = ['배포에 실패했습니다.'];

        $faqList = CsFaq::getPreviewList();

        if (!empty($faqList)) {
            // write file
            $faqFile = '/publish/faq.json';
            Storage::disk('public')->put($faqFile, json_encode($faqList));

            $error = false;
            $status = 200;
            $fileUrl = '/storage' . $faqFile;
            $messages = ['배포에 성공하였습니다.'];
        }

        return response()->json([
            'error' => $error,
            'fileUrl' => $fileUrl,
            'messages' => $messages
        ], $status);
    }

    /**
     * Show Notice (Normal)
     *
     * @return Renderable
     */
    public function notice()
    {
        return view('managements.notice');
    }

    /**
     * get Notice List
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function listNotice(Request $request)
    {
        $error = true;
        $noticeList = null;

        $status = 404;
        $messages = ['데이터를 찾지 못했습니다.'];

        $validator = Validator::make($request->input(), array(
            'from' => 'required'
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $params = [
            'startDate' => ($request->input('startDate') == "") ? -1 : $request->input('startDate'),
            'endDate' => ($request->input('endDate') == "") ? -1 : $request->input('endDate'),
            'game_type' => ($request->input('gameType') == "") ? -1 : $request->input('gameType'),
            'category' => ($request->input('category') == "") ? -1 : $request->input('category'),
            'status' => ($request->input('status') == "") ? -1 : $request->input('status'),
            'os' => ($request->input('osType') == "") ? -1 : $request->input('osType'),
            'admin_name' => $request->input('adminName'),
            'ordered' => $request->input('ordered'),
        ];

        $from = $request->input('from');

        if ($from == 'notice') {
            $error = false;
            $status = 200;
            $messages = null;

            $noticeList = CsNotice::getList($params);

        }

        return response()->json([
            'error' => $error,
            'noticeList' => $noticeList,
            'messages' => $messages
        ], $status);
    }

    /**
     * get Notice Article
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getNotice(Request $request)
    {
        $error = true;
        $noticeArticle = null;

        $status = 404;
        $messages = ['데이터를 찾지 못했습니다.'];

        $validator = Validator::make($request->input(), array(
            'id' => 'required',
            'from' => 'required'
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $params = [
            'id' => $request->input('id'),
        ];
        $from = $request->input('from');

        if ($from == 'notice') {
            $error = false;
            $status = 200;
            $messages = null;

            $noticeArticle = CsNotice::where('id', $params['id'])->first();

        }

        return response()->json([
            'error' => $error,
            'noticeArticle' => $noticeArticle,
            'messages' => $messages
        ], $status);
    }

    /**
     * write or update Notice
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateNotice(Request $request)
    {
        $error = true;
        $noticeList = null;

        $status = 404;
        $messages = ['데이터를 찾지 못했습니다.'];

        $validator = Validator::make($request->input(), array(
            'id' => 'required',
            'gameType' => 'required',
            'category' => 'required',
            'status' => 'required',
            'osType' => 'required',
            'title' => 'required',
            'content' => 'required',
            'isReserve' => 'required',
            'from' => 'required'
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        // 이미지 경로 절대경로로 변경
        $content = $request->input('content');
        $content = str_replace("src=\"/storage/upload/", "src=\"".env('APP_IMG_URL')."/upload/", $content);
        $params = [
            'id' => $request->input('id'),
            'game_type' => $request->input('gameType'),
            'category' => $request->input('category'),
            'status' => $request->input('status'),
            'os' => $request->input('osType'),
            'title' => $request->input('title'),
            'content' => $content,
            'reserve_start_date' => $request->input('reserveStartDate'),
            'reserve_end_date' => $request->input('reserveEndDate'),
            'mode' => $request->input('mode'),
            'checked_ids' => $request->input('checked_ids'),
        ];
        $isReserve = $request->input('isReserve');
        $isTempSave = $request->input('isTempSave');
        $from = $request->input('from');

        // pre check
        if ($isReserve == "true") {
            $params['status'] = 2;
        }
        if ($isTempSave == "true") {
            $params['status'] = 3;
        }

        // update by from
        if ($from == 'noticeEdit') {
            $error = false;
            $status = 200;
            $messages = ['정상 저장 되었습니다.'];

            $mode = $params['mode'];

            $this->adminUser = User::find(Auth::id());

            if ($mode == "new") {
                $notice = new CsNotice($params);
                $newOrder = CsNotice::getNewOrder();
                $notice->order = intval($newOrder->maxOrder) + 1;
                $notice->create_date = Carbon::now();
                $notice->update_date = Carbon::now();

            } else {
                $notice = CsNotice::where('id', $params['id'])->first();
                $notice->game_type = $params['game_type'];
                $notice->category = $params['category'];
                $notice->status = $params['status'];
                $notice->os = $params['os'];
                $notice->title = $params['title'];
                $notice->content = $params['content'];
                $notice->reserve_start_date = $params['reserve_start_date'];
                $notice->reserve_end_date = $params['reserve_end_date'];
                $notice->update_date = Carbon::now();
            }
            $notice->admin_id = $this->adminUser->id;
            $notice->admin_name = $this->adminUser->name;
            $notice->save();

        } else if ($from == 'noticeOrder') {
            $error = false;
            $status = 200;
            $messages = ['노출 순서가 변경되었습니다.'];

            $params['order'] = $request->input('order');
            $params['direction'] = $request->input('direction');

            CsNotice::changeOrder($params);

        } else if ($from == 'noticeDelete') {
            $error = false;
            $status = 200;
            $messages = ['정상 삭제 되었습니다.'];

            $notice = CsNotice::where('id', $params['id'])->first();
            $notice->is_delete = 1;
            $notice->save();

        } else if ($from == 'checkedShow') {
            $error = false;
            $status = 200;
            $messages = ['정상 수정 되었습니다.'];

            $ids = json_decode($params['checked_ids'], true);
            CsNotice::updateArticles($ids, 'status', 1);
        } else if ($from == 'checkedHide') {
            $error = false;
            $status = 200;
            $messages = ['정상 수정 되었습니다.'];

            $ids = json_decode($params['checked_ids'], true);
            CsNotice::updateArticles($ids, 'status', 0);

        } else if ($from == 'checkedDelete') {
            $error = false;
            $status = 200;
            $messages = ['정상 삭제 되었습니다.'];

            $ids = json_decode($params['checked_ids'], true);
            CsNotice::updateArticles($ids, 'is_delete', 1);
        }

        return response()->json([
            'error' => $error,
            'noticeList' => $noticeList,
            'messages' => $messages
        ], $status);
    }

    /**
     * Show FAQ.
     *
     * @return Renderable
     */
    public function faq()
    {
        return view('managements.faq');
    }

    /**
     * get Notice List
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function listFaq(Request $request)
    {
        $error = true;
        $faqList = null;

        $status = 404;
        $messages = ['데이터를 찾지 못했습니다.'];

        $validator = Validator::make($request->input(), array(
            'from' => 'required'
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $params = [
            'startDate' => ($request->input('startDate') == "") ? -1 : $request->input('startDate'),
            'endDate' => ($request->input('endDate') == "") ? -1 : $request->input('endDate'),
            'game_type' => ($request->input('gameType') == "") ? -1 : $request->input('gameType'),
            'category' => ($request->input('category') == "") ? -1 : $request->input('category'),
            'status' => ($request->input('status') == "") ? -1 : $request->input('status'),
            'os' => ($request->input('osType') == "") ? -1 : $request->input('osType'),
            'admin_name' => $request->input('adminName'),
            'ordered' => $request->input('ordered'),
        ];

        $from = $request->input('from');

        if ($from == 'faq') {
            $error = false;
            $status = 200;
            $messages = null;

            $faqList = CsFaq::getList($params);

        }

        return response()->json([
            'error' => $error,
            'faqList' => $faqList,
            'messages' => $messages
        ], $status);
    }

    /**
     * get Faq Article
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getFaq(Request $request)
    {
        $error = true;
        $faqArticle = null;

        $status = 404;
        $messages = ['데이터를 찾지 못했습니다.'];

        $validator = Validator::make($request->input(), array(
            'id' => 'required',
            'from' => 'required'
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $params = [
            'id' => $request->input('id'),
        ];
        $from = $request->input('from');

        if ($from == 'faq') {
            $error = false;
            $status = 200;
            $messages = null;

            $faqArticle = CsFaq::where('id', $params['id'])->first();

        }

        return response()->json([
            'error' => $error,
            'faqArticle' => $faqArticle,
            'messages' => $messages
        ], $status);
    }

    /**
     * write or update Notice
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateFaq(Request $request)
    {
        $error = true;
        $faqList = null;

        $status = 404;
        $messages = ['데이터를 찾지 못했습니다.'];

        $validator = Validator::make($request->input(), array(
            'id' => 'required',
            'gameType' => 'required',
            'category' => 'required',
            'status' => 'required',
            'osType' => 'required',
            'title' => 'required',
            'content' => 'required',
            'isReserve' => 'required',
            'from' => 'required'
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $content = $request->input('content');
        $content = str_replace("src=\"/storage/upload/", "src=\"".env('APP_URL')."/storage/upload/", $content);

        $params = [
            'id' => $request->input('id'),
            'game_type' => $request->input('gameType'),
            'category' => $request->input('category'),
            'status' => $request->input('status'),
            'os' => $request->input('osType'),
            'title' => $request->input('title'),
            'content' => $content,
            'reserve_start_date' => $request->input('reserveStartDate'),
            'reserve_end_date' => $request->input('reserveEndDate'),
            'mode' => $request->input('mode'),
            'checked_ids' => $request->input('checked_ids'),
        ];
        $isReserve = $request->input('isReserve');
        $isTempSave = $request->input('isTempSave');
        $from = $request->input('from');

        // pre check
        if ($isReserve == "true") {
            $params['status'] = 2;
        }
        if ($isTempSave == "true") {
            $params['status'] = 3;
        }

        // update by from
        if ($from == 'faqEdit') {
            $error = false;
            $status = 200;
            $messages = ['정상 저장 되었습니다.'];

            $mode = $params['mode'];

            $this->adminUser = User::find(Auth::id());

            if ($mode == "new") {
                $faq = new CsFaq($params);
                $newOrder = CsFaq::getNewOrder();
                $faq->order = intval($newOrder->maxOrder) + 1;
                $faq->create_date = Carbon::now();
                $faq->update_date = Carbon::now();

            } else {
                $faq = CsFaq::where('id', $params['id'])->first();
                $faq->game_type = $params['game_type'];
                $faq->category = $params['category'];
                $faq->status = $params['status'];
                $faq->os = $params['os'];
                $faq->title = $params['title'];
                $faq->content = $params['content'];
                $faq->reserve_start_date = $params['reserve_start_date'];
                $faq->reserve_end_date = $params['reserve_end_date'];
                $faq->update_date = Carbon::now();
            }
            $faq->admin_id = $this->adminUser->id;
            $faq->admin_name = $this->adminUser->name;
            $faq->save();

        } else if ($from == 'faqOrder') {
            $error = false;
            $status = 200;
            $messages = ['노출 순서가 변경되었습니다.'];

            $params['order'] = $request->input('order');
            $params['direction'] = $request->input('direction');

            CsFaq::changeOrder($params);

        } else if ($from == 'faqDelete') {
            $error = false;
            $status = 200;
            $messages = ['정상 삭제 되었습니다.'];

            $faq = CsFaq::where('id', $params['id'])->first();
            $faq->is_delete = 1;
            $faq->save();

        } else if ($from == 'checkedShow') {
            $error = false;
            $status = 200;
            $messages = ['정상 수정 되었습니다.'];

            $ids = json_decode($params['checked_ids'], true);
            CsFaq::updateArticles($ids, 'status', 1);
        } else if ($from == 'checkedHide') {
            $error = false;
            $status = 200;
            $messages = ['정상 수정 되었습니다.'];

            $ids = json_decode($params['checked_ids'], true);
            CsFaq::updateArticles($ids, 'status', 0);

        } else if ($from == 'checkedDelete') {
            $error = false;
            $status = 200;
            $messages = ['정상 삭제 되었습니다.'];

            $ids = json_decode($params['checked_ids'], true);
            CsFaq::updateArticles($ids, 'is_delete', 1);
        }

        return response()->json([
            'error' => $error,
            'faqList' => $faqList,
            'messages' => $messages
        ], $status);
    }

    /**
     * Manage version
     *
     * @return View|Factory
     */
    public function version()
    {
        return view('managements.version', ['data'=>[]]);
    }

    /**
     * Get version list
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function listVersion(Request $request)
    {
        $validator = Validator::make($request->input(), array(
            'type' => 'required'
        ));
        if($validator->fails()) {
            $errMsg = '';
            foreach($validator->errors()->toArray() as $err) {
                $errMsg = $err[0];
                break;
            }
            return response()->json([
                'error' => true,
                'messages' => $errMsg,
            ], 200);
        }

        $lists = null;
        if($request->input('type') == 'client') {
            $lists = ClientVersion::orderBy('version')->get();
        } elseif($request->input('type') == 'server') {
            $lists = ServerVersion::orderBy('version')->get();
        }

        return response()->json(['result' => 0, 'data' => $lists, 'messages' => null], 200);
    }

    /**
     * Set version
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setVersion(Request $request)
    {

        $validator = Validator::make($request->input(), array(
            'type' => 'required',
            'version' => 'required',
        ));
        if($validator->fails()) {
            $errMsg = '';
            foreach($validator->errors()->toArray() as $err) {
                $errMsg = $err[0];
                break;
            }
            return response()->json([
                'error' => true,
                'messages' => $errMsg,
            ], 200);
        }

        $setVer = $request->input('version');

        if($request->input('type') == 'client') {
            ClientVersion::where('version', '=', $request->input('old_version'))->delete();
            ClientVersion::updateOrCreate(
                ['version' => $setVer],
                ['update_date' => Carbon::now()]
            );
        } elseif($request->input('type') == 'server') {
            $validator = Validator::make($request->input(), array(
                'desc' => 'required',
                'cdn' => 'required',
                // 'web_lobby' => 'required',
                'web_world' => 'required',
                'slot_server' => 'required',
                'slot_lobby' => 'required',
            ));

            if($validator->fails()) {
                $errMsg = '';
                foreach($validator->errors()->toArray() as $err) {
                    $errMsg = $err[0];
                    break;
                }

                return response()->json([
                    'error' => true,
                    'messages' => $errMsg,
                ], 200);
            }

            $server_notice = $request->input('server_notice');
            $tournament_lobby = $request->input('tournament_lobby');
            $http_lobby = $request->input('http_lobby');
            if(empty($server_notice)) $server_notice = null;
            if(empty($tournament_lobby)) $tournament_lobby = '';
            if(empty($http_lobby)) $http_lobby = '';


            $bindValues = [];

            if ($request->input('idx') !== null)
                $bindValues['idx'] = $request->input('idx');

            $bindValues['version']              = $setVer;
            $bindValues['`desc`']               = $request->input('desc');
            $bindValues['cdn']                  = $request->input('cdn');
            $bindValues['web_world']            = $request->input('web_world');
            $bindValues['slot_server']          = $request->input('slot_server');
            $bindValues['slot_lobby']           = $request->input('slot_lobby');
            $bindValues['tournament_lobby']     = $tournament_lobby;
            $bindValues['http_lobby']           = $http_lobby;
            $bindValues['`server_status`']      = $request->input('server_status');
            $bindValues['`server_notice`']      = $server_notice;

            //
            // DB UPSERT
            //
            $updStatus = ServerVersion::upsert('`accountdb`.`version_url`', $bindValues);

            // if (! $updStatus)
            //     return response()->json(['error' => true, 'messages' => '버전등록/수정이 실패하였습니다.'], 200);

            $endPoint = '/sync-version';

            //
            //버전 갱신 API 호출
            //
            $syncVerRes = Http::withHeaders([
                            'Authorization' => 'Bearer '.env('API_BEARER_TOKEN'),
                            'Accept'        => '*/*',
                        ])->get(env('API_URL').$endPoint);

            if ($syncVerRes->failed())
                return response()->json(['error' => true, 'messages' => '버전등록/수정은 성공했으나, 갱신에는 실패했습니다. 갱신버튼을 눌러주세요!'], 200);

        }

        return response()->json(['result' => 1, 'messages' => '버전이 등록/수정 되었습니다.'], 200);
    }

    /**
     * Drop version
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function dropVersion(Request $request)
    {
        $validator = Validator::make($request->input(), array(
            'type' => 'required',
        ));
        if($validator->fails()) {
            $errMsg = '';
            foreach($validator->errors()->toArray() as $err) {
                $errMsg = $err[0];
                break;
            }
            return response()->json([
                'error' => true,
                'messages' => $errMsg,
            ], 200);
        }

        if($request->input('type') == 'client') {
            ClientVersion::find($request->input('version'))->delete();
        } elseif($request->input('type') == 'server') {

            $delStatus = ServerVersion::find($request->input('idx'))->delete();

            if (! $delStatus)
                return response()->json(['result' => 0, 'messages' => '버전 삭제에 실패했습니다.'], 200);

            $endPoint = '/sync-version';

            $syncVerRes = Http::withHeaders([
                            'Authorization' => 'Bearer '.env('API_BEARER_TOKEN'),
                            'Accept'        => '*/*',
                        ])->get(env('API_URL').$endPoint);

            if ($syncVerRes->failed())
            {
                return response()->json(['result' => 0, 'messages' => '버전 삭제했으나 갱신에는 실패했습니다. 갱신 버튼을 눌러주세요!'], 200);
            }
        }

        return response()->json(['result' => 1, 'messages' => '버전이 삭제되었습니다.'], 200);
    }

    /**
     * Manage whitelist
     *
     * @return View|Factory
     */
    public function whitelist()
    {
        return view('managements.whitelist', ['data'=>[]]);
    }

    /**
     * Get whitelist list
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function listWhitelist(Request $request)
    {
        $lists = Whitelist::orderBy('idx', 'desc')->get();
        $cnt = $lists->count();
        foreach($lists as $idx => $row) {
            $row->no = $cnt;
            $cnt--;
        }
        return response()->json(['result' => 0, 'data' => $lists, 'messages' => null], 200);
    }

    /**
     * Set whitelist
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setWhitelist(Request $request)
    {
        $validator = Validator::make($request->input(), array(
            'ip' => 'required',
        ));
        if($validator->fails()) {
            $errMsg = '';
            foreach($validator->errors()->toArray() as $err) {
                $errMsg = $err[0];
                break;
            }
            return response()->json([
                'result' => 0,
                'messages' => $errMsg,
            ], 200);
        }
        $record = Whitelist::where('ip', '=', $request->input('ip'))->get();

        if($record->count() > 0) {
            return response()->json(['result' => 0, 'messages' => '이미 등록된 IP주소 입니다.'], 200);
        }


        $newIp = Whitelist::create([
            'ip' => $request->input('ip'),
            'description' => $request->input('description'),
            'create_datetime' => Carbon::now(),
        ]);

        //
        // 생성여부확인
        //
        if (! $newIp->idx)
            return response()->json(['result' => 0, 'messages' => 'IP주소 등록에 실패했습니다.'], 200);

        $endPoint = '/sync-whitelist';

        $syncWhiteRs = Http::withHeaders([
                        'Authorization' => 'Bearer '.env('API_BEARER_TOKEN'),
                        'Accept'        => '*/*',
                    ])->get(env('API_URL').$endPoint);


        if ($syncWhiteRs->failed())
        {
            return response()->json(['result' => 0, 'messages' => 'IP주소 등록은 성공했으나, 갱신은 실패하였습니다. 재갱신해주세요.'], 200);
        }

        return response()->json(['result' => 1, 'messages' => 'IP주소가 등록되었습니다.'], 200);
    }

    /**
     * Drop whitelist
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function dropWhitelist(Request $request)
    {
        $validator = Validator::make($request->input(), array(
            'idx' => 'required',
        ));
        if($validator->fails()) {
            return response()->json([
                'result' => 0,
                'messages' => '필수 항목값이 없습니다.',
            ], 200);
        }

        $delStatus = Whitelist::find($request->input('idx'))->delete();

        if (! $delStatus)
            return response()->json(['result' => 0, 'messages' => 'IP주소 삭제에 실패했습니다.'], 200);

        $endPoint = '/sync-whitelist';

        $syncWhiteRs = Http::withHeaders([
                        'Authorization' => 'Bearer '.env('API_BEARER_TOKEN'),
                        'Accept'        => '*/*',
                    ])->get(env('API_URL').$endPoint);


        if ($syncWhiteRs->failed())
        {
            return response()->json(['result' => 0, 'messages' => 'IP주소 등록은 성공했으나 갱신실패하였습니다. 재갱신해주세요.'], 200);
        }

        return response()->json(['result' => 1, 'messages' => 'IP주소가 삭제되었습니다.'], 200);
    }

    /**
     * Rolling Notice
     *
     * @return View
     */
    public function rolling()
    {
        return view('managements.rolling');
    }

    /**
     * Get Rolling Notice list
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function listRolling(Request $request)
    {
        $lists = RollingNotice::orderBy('idx', 'desc')->get();
        $cnt = $lists->count();
        $currDateTime = Carbon::now()->format('Y-m-d H:i') . ':00';
        foreach($lists as $idx => $row) {
            $row->no = $cnt;
            $row->status = '<span class="bg-success text-light">예약중</span>';
            if($row->noti_count <= $row->noti_counted || $row->expire_datetime < $currDateTime) $row->status = '<span class="bg-secondary text-light">만료됨</span>';
            elseif($row->start_datetime <= $currDateTime && $row->expire_datetime > $currDateTime) $row->status = '<span class="bg-primary text-light">진행중</span>';
            $cnt--;
        }
        return response()->json(['result' => 0, 'data' => $lists, 'messages' => null], 200);
    }

    /**
     * Set Rolling Notice
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setRolling(Request $request)
    {
        $validator = Validator::make($request->input(), array(
            'noti_count' => 'required',
            'noti_interval' => 'required',
            'message' => 'required',
            'start_date' => 'required',
            'start_time' => 'required',
        ));
        if($validator->fails()) {
            $errField = '';
            foreach($validator->errors()->toArray() as $k => $v) {
                $errField = $k;
                break;
            }
            switch($errField) {
                case 'noti_count':
                    $errMsg = '발송횟수는';
                    break;
                case 'noti_interval':
                    $errMsg = '발송간격은';
                    break;
                case 'message':
                    $errMsg = '발송내용은';
                    break;
                case 'start_date':
                    $errMsg = '시작날짜는';
                    break;
                default:
                    $errMsg = '시작시간은';
            }
            return response()->json([
                'result' => 0,
                'messages' => $errMsg . ' 필수 항목입니다.',
            ], 200);
        }
        $noti_count = $request->input('noti_count');
        $noti_interval = $request->input('noti_interval');
        $dday = $request->input('start_date') . ' ' . $request->input('start_time');
        $start_datetime = $dday . ':00';
        $expire_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $start_datetime)->addMinutes(($noti_count - 1) * $noti_interval)->toDateTimeString();
        $dday = preg_replace('/\D/', '', $dday);
        RollingNotice::create([
            'noti_count' => $noti_count,
            'noti_counted' => 0,
            'noti_interval' => $noti_interval,
            'message' => $request->input('message'),
            'start_datetime' => $start_datetime,
            'expire_datetime' => $expire_datetime,
            'dday' => $dday,
            'create_datetime' => Carbon::now()->toDateTimeString(),
        ]);
        return response()->json(['result' => 1, 'messages' => '롤링공지가 등록되었습니다.'], 200);
    }

    /**
     * Drop Rolling Notice
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function dropRolling(Request $request)
    {
        $validator = Validator::make($request->input(), array(
            'idx' => 'required',
        ));
        if($validator->fails()) {
            return response()->json([
                'result' => 0,
                'messages' => '필수 항목값이 없습니다.',
            ], 200);
        }
        RollingNotice::find($request->input('idx'))->delete();
        return response()->json(['result' => 1, 'messages' => '롤링공지가 삭제되었습니다.'], 200);
    }

    /**
     * 토너먼트 리스트
     *
     * @param  \Illuminate\Http\Request $request
     * @param  bool $pagination
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection
     */
    private function tournamentList(Request $request, bool $pagination)
    {
        $sDateTime = $request->input('search_start_date') ?? $this->today . ' 00:00:00';
        $eDateTime = $request->input('search_end_date') ?? $this->today . ' 23:59:59';
        $money = $request->input('search_money') ?? 'all';
        $name = $request->input('search_name') ?? '';
        $list = $request->input('list') ?? 20;

        $builder = Tournament::select('tid', 'title', 'start_date', 'status', 'entry_min', 'entry_max', 'reward_type', 'reward', 'money', 're_buy', 're_entry', 'addon', 'blind_lvup_time', 'buyin_offer_chip', 'buyin_cash', 'total_buyin')
            ->whereBetween('start_date', [$sDateTime, $eDateTime]);
        if($money != 'all') $builder = $builder->where('money', '=', $money);
        if(!empty($name)) $builder = $builder->where('title', 'like', '%' . $name . '%');
        $builder = $builder->orderBy('tid', 'DESC');

        $records = $pagination ? $builder->paginate($list) : $builder->get();
        foreach($records as $row) {
            $row->people = $this->getTournamentRegMember($row->tid)->count();
        }
        return $records;
    }

    /**
     * 토너먼트 참가멤버
     *
     * @param  int $tid
     * @param  bool $reward
     * @return \Illuminate\Database\Query\Builder
     */
    private function getTournamentRegMember(int $tid, bool $reward = false)
    {
        $exists = Helper::existsTable('tournament', 'member_' . $tid);
        if($exists) {
            $builder = DB::connection('mysql')->table('tournament.member_' . $tid . ' AS M')
                ->join('accountdb.account_info AS A', 'M.user_seq', '=', 'A.user_seq');
        } else {
            $builder = DB::connection('mysql')->table('tournament.backup_member AS M')
                ->join('accountdb.account_info AS A', function($join) use ($tid) {
                    $join->on('M.user_seq', '=', 'A.user_seq')->where('M.tid', '=', $tid);
                });
        }
        if($reward) {
            $builder = $builder->leftJoin('tournament.member_reward AS R', function($join) use ($tid) {
                $join->on('M.user_seq', '=', 'R.user_seq')->where('R.tid', '=', $tid);
            });
        }

        return $builder;
    }

    /**
     * 토너먼트 조회
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function tournament(Request $request)
    {
        $sDateTime = $request->input('search_start_date') ?? $this->today . ' 00:00:00';
        $eDateTime = $request->input('search_end_date') ?? $this->today . ' 23:59:59';
        $money = $request->input('search_money') ?? 'all';
        $name = $request->input('search_name') ?? '';

        $records = $this->tournamentList($request, true);
        $records->withPath(route('managements.tournament'));
        $numberStart = $records->total() - (($records->currentPage() - 1) * $records->perPage());

        $data = [
            'search' => ['sdate' => $sDateTime, 'edate' => $eDateTime, 'money' => $money, 'name' => $name],
            'records' => $records,
            'numberStart' => $numberStart,
            'now' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
        return view('managements.tournament', $data);
    }

    /**
     * 토너먼트 상세보기
     *
     * @param  int $tid
     * @return \Illuminate\View\View
     */
    public function tournamentDetail(int $tid)
    {
        $record = Tournament::find($tid);
        $record->people = $this->getTournamentRegMember($tid)->count();
        $data = ['record' => $record];
        return view('managements.tournamentDetail', $data);
    }

    /**
     * 토너먼트 엑셀 다운 for AJAX
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function tournamentExcel(Request $request)
    {
        $records = $this->tournamentList($request, false);
        $numberStart = $records->count();
        $output = [];
        $output[] = ['No.', '토너먼트 이름', '시작일', '상태', '현재 등록인원', '최소/최대 인원', '게런티', '리바이', '리엔트리', '애드온', '레벨업(분)', '시작칩', '바이인', '총 바이인 금액'];
        foreach($records as $row) {
            $output[] = [
                $numberStart--,
                $row->title,
                $row->start_date,
                Helper::getTournamentStatus($row->status),
                $row->people,
                $row->entry_min . ' / ' . $row->entry_max,
                $row->reward_type == 1 ? Helper::numberToKorean(max($row->reward, $row->total_buyin)) . ($row->money == '2018' ? '골드' : '칩') : $row->reward,
                $row->re_buy,
                $row->re_entry,
                $row->addon,
                $row->blind_lvup_time,
                Helper::numberToKorean($row->buyin_offer_chip),
                Helper::numberToKorean($row->buyin_cash) . ($row->money == '2018' ? '골드' : '칩'),
                Helper::numberToKorean($row->total_buyin) . ($row->money == '2018' ? '골드' : '칩'),
            ];
        }
        return response()->json(['numberStart' => $numberStart, 'records' => $output], 200);
    }

    /**
     * 토너먼트 등록
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function tournamentReg(Request $request)
    {
        $title = $request->input('title');
        $money = $request->input('money');
        $startDate = $request->input('start_date');
        $playerCnt = $request->input('player_cnt');
        $entryMin = $request->input('entry_min');
        $entryMax = $request->input('entry_max');
        $buyInCash = $request->input('buyin_cash');
        $buyInOfferChip = $request->input('buyin_offer_chip');
        $blindType = $request->input('blind_type');
        $blindLvUpTime = $request->input('blind_lvup_time');
        $breakTime = $request->input('break_time');
        $breakTimeCycle = $request->input('break_time_cycle');
        $reBuy = $request->input('re_buy');
        $reEntry = $request->input('re_entry');
        $addon = $request->input('addon');
        $rewardType = $request->input('reward_type');
        $reward = $request->input('reward');
        $rewardTicket = $request->input('reward_ticket');
        $addonOfferChip = $request->input('addon_offer_chip');
        $rewardMinPlayer = $request->input('reward_min_player');
        $useBuyinTicket = $request->input('use_buyin_ticket');
        $headline = $request->input('headline') ?? '';
        $colorHeadline = $request->input('color_headline') ?? '';
        $colorTitle = $request->input('color_title') ?? '';
        $tableResource = $request->input('table_resource') ?? '';
        $addon_cash = $request->input('addon_cash');

        $params = [
            'title'             => $title,
            'money'             => $money,
            'start_date'        => $startDate,
            'player_cnt'        => $playerCnt,
            'entry_min'         => $entryMin,
            'entry_max'         => $entryMax,
            'buyin_cash'        => $buyInCash,
            'buyin_offer_chip'  => $buyInOfferChip,
            'blind_type'        => $blindType,
            'blind_lvup_time'   => $blindLvUpTime,
            'breake_time'       => $breakTime,
            'breake_time_cycle' => $breakTimeCycle,
            're_buy'            => $reBuy,
            're_entry'          => $reEntry,
            'reward_type'       => $rewardType,
            'reward'            => $reward,
            'reward_ticket'     => $rewardTicket,
            'addon'             => $addon,
            'addon_cash'        => $addon_cash,
            'addon_offer_chip'  => $addonOfferChip,
            'reward_min_player' => $rewardMinPlayer,
            'use_buyin_ticket'  => $useBuyinTicket,
            'headline'          => $headline,
            'color_headline'    => $colorHeadline,
            'color_title'       => $colorTitle,
            'table_resource'    => $tableResource,
        ];

        if(substr(PHP_VERSION, 0, 1) === '8') {
            $newId = Tournament::insertGetId($params);
            DB::connection('mysql')->statement('CREATE TABLE `tournament`.`member_' . $newId . '` LIKE `tournament`.`member_tid`');
        } else {
            DB::beginTransaction();

            try {
                $newId = Tournament::insertGetId($params);
            } catch(\Exception $e) {
                DB::rollback();
                return response()->json(['result' => false, 'message' => 'DB error'], 200);
            }

            try {
                DB::connection('mysql')->statement('CREATE TABLE `tournament`.`member_' . $newId . '` LIKE `tournament`.`member_tid`');
            } catch(\Exception $e) {
                DB::rollback();
                return response()->json(['result' => false, 'message' => 'DB error'], 200);
            }

            DB::commit();
        }
        $this->adminUser = User::find(Auth::id());
        // admin Log
        AdminLog::create([
            'type' => 'tournament_create',
            'menu' => 'management',
            'action' => 'createTournament',
            'log_type' => 'admin',
            'params' => json_encode($params),
            'reason' => Helper::adminLogType('admin'),
            'extra' => '신규등록',
            'user_seq' => 0,
            'nickname' => '',
            'before_state' => 0,
            'after_state' => 0,
            'admin_id' => $this->adminUser->id,
            'admin_name' => $this->adminUser->name,
            'created_at' => DB::raw('now()'),
            'updated_at' => DB::raw('now()'),
        ]);
        return response()->json(['result' => true, 'message' => 'Success'], 200);
    }

    /**
     * 토너먼트 등록인원
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function tournamentRegMember(Request $request)
    {
        $tid = $request->input('tid');
        $page = $request->input('page');
        $list = 20;
        $pageSize = 10;

        $tournament = Tournament::find($tid);
        $closed = in_array($tournament->status, ['5', '6']);
        $accountInfo = Helper::getAccountInfo($request->input('search_type'), $request->input('login_type'), $request->input('keyword'));
        $builder = $this->getTournamentRegMember($tid, $closed);
        if($accountInfo) $builder = $builder->where('M.user_seq', $accountInfo->user_seq);

        $recordCnt = $builder->count();
        if($recordCnt == 0) {
            $data = [
                'record_cnt' => 0,
                'records' => null,
                'start_num' => 0,
                'closed' => false,
                'pagination' => Helper::getPagination($list, $page, $pageSize, 0)
            ];
            return response()->json(['data' => $data], 200);
        }

        $page = intval($page);
        $start = ($page - 1) * $list;
        $startNum = $recordCnt - $start;

        if($closed) {
            $builder = $builder->selectRaw("M.*, A.nickname, IFNULL(R.rank, 99999) AS rank, IFNULL(R.reward, 0) AS reward, IFNULL(reward_date, '') AS reward_date")
            ->orderBy('rank', 'ASC');
        } else {
            $builder = $builder->selectRaw("M.*, A.nickname, 0 AS rank, 0 AS reward, '' AS reward_date")
            ->orderBy('M.updatedate', 'ASC');
        }

        $records = $builder->skip($start)->take($list)->get();

        $data = [
            'record_cnt' => $recordCnt,
            'records' => $records,
            'start_num' => $startNum,
            'closed' => $closed,
            'pagination' => Helper::getPagination($list, $page, $pageSize, $recordCnt),
        ];

        return response()->json(['data' => $data], 200);
    }

    /**
     * 데이터 동기화 요청
     *
     * @param Request $request
     * @return void
     */
    public function syncData(Request $request)
    {
        $endPoint = '/sync-'.($request->post('action') ?? '');

        $setHeader = [
            'Authorization' => 'Bearer '.env('API_BEARER_TOKEN'),
            'Accept'        => '*/*',
        ];

        $result = 1;
        $httpCode = 200;
        $message = '데이터 갱신 성공!';

        try {

            $syncRes = Http::withHeaders($setHeader)->get(env('API_URL').$endPoint);

            if ($syncRes->fail())
                throw new Exception();

        } catch (Exception $e) {

            $result = 0;
            $message = '데이터 갱신 실패!';

        } finally {

            return response()->json(['result' => $result, 'message' => $message ], $httpCode);
        }


    }
}
