<?php

namespace App\Http\Controllers;

use App\Model\CMS\CsSlotFaq;
use App\Model\CMS\CsSlotNotice;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class SlotManagementController extends Controller
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

        $noticeList = CsSlotNotice::getPreviewList();

        if (!empty($noticeList)) {
            // write file
            $noticeFile = '/preview/slot/notice.json';
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

        $noticeList = CsSlotNotice::getPreviewList();

        if (!empty($noticeList)) {
            // write file
            $noticeFile = '/publish/slot/notice.json';
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

        $faqList = CsSlotFaq::getPreviewList();

        if (!empty($faqList)) {
            // write file
            $faqFile = '/preview/slot/faq.json';
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

        $faqList = CsSlotFaq::getPreviewList();

        if (!empty($faqList)) {
            // write file
            $faqFile = '/publish/slot/faq.json';
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
        return view('managements.slot.notice');
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

            $noticeList = CsSlotNotice::getList($params);

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

            $noticeArticle = CsSlotNotice::where('id', $params['id'])->first();

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
                $notice = new CsSlotNotice($params);
                $newOrder = CsSlotNotice::getNewOrder();
                $notice->order = intval($newOrder->maxOrder) + 1;
                $notice->create_date = Carbon::now();
                $notice->update_date = Carbon::now();

            } else {
                $notice = CsSlotNotice::where('id', $params['id'])->first();
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

            CsSlotNotice::changeOrder($params);

        } else if ($from == 'noticeDelete') {
            $error = false;
            $status = 200;
            $messages = ['정상 삭제 되었습니다.'];

            $notice = CsSlotNotice::where('id', $params['id'])->first();
            $notice->is_delete = 1;
            $notice->save();

        } else if ($from == 'checkedShow') {
            $error = false;
            $status = 200;
            $messages = ['정상 수정 되었습니다.'];

            $ids = json_decode($params['checked_ids'], true);
            CsSlotNotice::updateArticles($ids, 'status', 1);
        } else if ($from == 'checkedHide') {
            $error = false;
            $status = 200;
            $messages = ['정상 수정 되었습니다.'];

            $ids = json_decode($params['checked_ids'], true);
            CsSlotNotice::updateArticles($ids, 'status', 0);

        } else if ($from == 'checkedDelete') {
            $error = false;
            $status = 200;
            $messages = ['정상 삭제 되었습니다.'];

            $ids = json_decode($params['checked_ids'], true);
            CsSlotNotice::updateArticles($ids, 'is_delete', 1);
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
        return view('managements.slot.faq');
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

            $faqList = CsSlotFaq::getList($params);

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

            $faqArticle = CsSlotFaq::where('id', $params['id'])->first();

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
                $faq = new CsSlotFaq($params);
                $newOrder = CsSlotFaq::getNewOrder();
                $faq->order = intval($newOrder->maxOrder) + 1;
                $faq->create_date = Carbon::now();
                $faq->update_date = Carbon::now();

            } else {
                $faq = CsSlotFaq::where('id', $params['id'])->first();
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

            CsSlotFaq::changeOrder($params);

        } else if ($from == 'faqDelete') {
            $error = false;
            $status = 200;
            $messages = ['정상 삭제 되었습니다.'];

            $faq = CsSlotFaq::where('id', $params['id'])->first();
            $faq->is_delete = 1;
            $faq->save();

        } else if ($from == 'checkedShow') {
            $error = false;
            $status = 200;
            $messages = ['정상 수정 되었습니다.'];

            $ids = json_decode($params['checked_ids'], true);
            CsSlotFaq::updateArticles($ids, 'status', 1);
        } else if ($from == 'checkedHide') {
            $error = false;
            $status = 200;
            $messages = ['정상 수정 되었습니다.'];

            $ids = json_decode($params['checked_ids'], true);
            CsSlotFaq::updateArticles($ids, 'status', 0);

        } else if ($from == 'checkedDelete') {
            $error = false;
            $status = 200;
            $messages = ['정상 삭제 되었습니다.'];

            $ids = json_decode($params['checked_ids'], true);
            CsSlotFaq::updateArticles($ids, 'is_delete', 1);
        }

        return response()->json([
            'error' => $error,
            'faqList' => $faqList,
            'messages' => $messages
        ], $status);
    }

}
