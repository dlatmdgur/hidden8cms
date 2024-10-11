@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('managements.notice') }}">운영관리</a></li>
                <li class="breadcrumb-item">포커 FAQ</li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h2>FAQ 등록/관리 (포커)</h2>
                                </div>
                            </div>
                        </div>


                        @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                        @endif

                        <div class="x_content">
                            <table class="table-bordered info-table" style="width: 100%">
                                <tr>
                                    <th>날짜 선택</th>
                                    <td>
                                        <div class="col-form-label col-md-3 col-sm-3 label-align">
                                            <div class="input-group date" id="datepicker">
                                                <input type="text" class="form-control" id="search_start_date1" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-form-label col-md-3 col-sm-3 label-align">
                                            <div class="input-group date" id="datepicker2">
                                                <input type="text" class="form-control" id="search_end_date1" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-form-label col-md-6 col-sm-6 label-align">
                                            <button type="button" class="btn btn-secondary period-reset" target="1">날짜 초기화</button> &nbsp;
                                            <button type="button" class="btn btn-primary period-selector" period="1" target="1">1일</button> &nbsp;
                                            <button type="button" class="btn btn-primary period-selector" period="3" target="1">3일</button> &nbsp;
                                            <button type="button" class="btn btn-primary period-selector" period="7" target="1">7일</button>
                                            <button type="button" class="btn btn-success period-selector" period="30" target="1">1개월</button> &nbsp;
                                            <button type="button" class="btn btn-success period-selector" period="90" target="1">3개월</button> &nbsp;
                                            <button type="button" class="btn btn-success period-selector" period="180" target="1">6개월</button>
                                            <button type="button" class="btn btn-warning period-selector" period="365" target="1">1년</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="col-form-label col-md-2 col-sm-2 label-align">
                                            <select class="form-control" id="game_type">
                                                <option value="">게임</option>
                                                <option value="0">공통</option>
                                                <option value="3">바카라</option>
                                                <option value="4">바둑이</option>
                                                <option value="5">하이로우</option>
                                                <option value="6">세븐포커</option>
                                                <option value="7">텍사스홀덤</option>
                                            </select>
                                        </div>
                                        <div class="col-form-label col-md-2 col-sm-2 label-align">
                                            <select class="form-control" id="category">
                                                <option value="">카테고리</option>
                                                <option value="1">회원정보</option>
                                                <option value="2">게임실행/설치</option>
                                                <option value="3">유료서비스</option>
                                                <option value="4">게임</option>
                                                <option value="5">신고/제한</option>
                                                <option value="6">이벤트</option>
                                                <option value="9">기타</option>
                                            </select>
                                        </div>
                                        <div class="col-form-label col-md-2 col-sm-2 label-align">
                                            <select class="form-control" id="status">
                                                <option value="">상태</option>
                                                <option value="2">예약</option>
                                                <option value="1">노출</option>
                                                <option value="0">비노출</option>
                                                <option value="3">임시저장</option>
                                            </select>
                                        </div>
                                        <div class="col-form-label col-md-2 col-sm-2 label-align">
                                            <select class="form-control" id="os_type">
                                                <option value="">OS</option>
                                                <option value="all">All</option>
                                                <option value="aos">AOS</option>
                                                <option value="ios">IOS</option>
                                            </select>
                                        </div>
                                        <div class="col-form-label col-md-3 col-sm-3 label-align">
                                            <div class="input-group">
                                                <span style="padding: 0 15px; font-size: 13px; line-height: 35px; font-weight: bold; color: #212529;"> 등록자 </span>
                                                <input type="text" class="form-control" id="admin_name" />
                                            </div>
                                        </div>
                                        <div class="col-form-label col-md-1 col-sm-1 label-align">
                                            <button type="button" id="btn-search-log" class="btn btn-secondary">검색</button>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="x_content">
                            <div class="row col-md-12 col-sm-12">
                                <div class="col-form-label col-md-4 col-sm-4">
                                    <span style="font-weight: bold;"> 선택 항목 : </span>
                                    <button type="button" id="btn-checked-show" class="btn btn-info btn-sm">노출</button>
                                    <button type="button" id="btn-checked-hide" class="btn btn-secondary btn-sm">비노출</button>
                                    <button type="button" id="btn-checked-delete" class="btn btn-danger btn-sm">삭제</button>
                                </div>
                                <div class="col-form-label col-md-4 col-sm-4 text-center">
                                    <h2 id="sort-text">등록일시 정렬</h2>
                                </div>
                                <div class="col-form-label col-md-4 col-sm-4 text-right">
                                    <button type="button" id="btn-ordered" class="btn btn-primary btn-small">노출순서보기</button>
                                    <button type="button" id="btn-preview" class="btn btn-info btn-small">미리보기</button>
                                    <button type="button" id="btn-publish" class="btn btn-success btn-small">배포</button>
                                </div>
                            </div>
                            <table class="table table-bordered info-table" id="faq_table" style="width:100%;">
                                <thead>
                                <tr>
                                    <th rowspan="2" width="3%"><input type="checkbox" id="check_all" name="all" value="all"></th>
                                    <th rowspan="2" width="4%">No</th>
                                    <th colspan="2" width="6%">노출순서</th>
                                    <th rowspan="2" width="6%">게임</th>
                                    <th rowspan="2" width="6%">카테고리</th>
                                    <th rowspan="2" width="35%">제목</th>
                                    <th rowspan="2" width="5%">OS</th>
                                    <th rowspan="2" width="5%">상태</th>
                                    <th rowspan="2" width="10%">등록자</th>
                                    <th rowspan="2" width="10%">등록일시</th>
                                    <th rowspan="2" width="10%">...</th>
                                </tr>
                                <tr style="display: none">
                                    <th width="3%">▲</th>
                                    <th width="3%">▼</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                        <div class="x_panel editor-box">
                            <div class="x_title">
                                <h2>FAQ 등록 / 수정 [ 현재 모드 : <span id="cur_mode">등록</span> ] <small id="cur_article"></small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <table class="table-bordered info-table edit-table" style="width: 100%;">
                                    <tr>
                                        <th width="5%">제목</th>
                                        <td width="60%"><input type="text" class="form-control" id="edit_title" /></td>
                                        <td><select class="form-control" id="edit_game_type">
                                                <option value="">게임</option>
                                                <option value="0">공통</option>
                                                <option value="3">바카라</option>
                                                <option value="4">바둑이</option>
                                                <option value="5">하이로우</option>
                                                <option value="6">세븐포커</option>
                                                <option value="7">텍사스홀덤</option>
                                            </select></td>
                                        <td><select class="form-control" id="edit_category">
                                                <option value="">카테고리</option>
                                                <option value="1">회원정보</option>
                                                <option value="2">게임실행/설치</option>
                                                <option value="3">유료서비스</option>
                                                <option value="4">게임</option>
                                                <option value="5">신고/제한</option>
                                                <option value="6">이벤트</option>
                                                <option value="9">기타</option>
                                            </select></td>
                                        <td><select class="form-control" id="edit_os_type">
                                                <option value="">OS</option>
                                                <option value="all">All</option>
                                                <option value="aos">AOS</option>
                                                <option value="ios">IOS</option>
                                            </select></td>
                                        <td><select class="form-control" id="edit_status">
                                                <option value="">상태</option>
                                                <option value="2">예약</option>
                                                <option value="1">노출</option>
                                                <option value="0">비노출</option>
                                                <option value="3">임시저장</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <th>등록</th>
                                        <td colspan="5" class="text-left">
                                            <div class="col-form-label col-md-2 col-sm-2 label-align text-left" style="padding-bottom: 0; line-height: 35px;">
                                                <input type="radio" class="flat" name="is_reserve" value="false" checked /> 바로 등록 &nbsp;
                                                <input type="radio" class="flat" name="is_reserve" value="true" /> 예약 등록 &nbsp;
                                            </div>
                                            <div class="col-form-label col-md-1 col-sm-1 label-align" style="padding-bottom: 0; line-height: 35px">
                                                Start Date &nbsp;
                                            </div>
                                            <div class="col-form-label col-md-2 col-sm-2 label-align text-right" style="padding-bottom: 0; line-height: 35px">
                                                <div class="input-group date" id="datepicker3" style="margin-bottom: 5px;">
                                                    <input type="text" class="form-control" id="reserve_start_date" />
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-form-label col-md-2 col-sm-2 label-align text-right" style="padding-bottom: 0;">
                                                <div class="input-group date" id="datepicker5" style="position:relative; padding-bottom: 0;">
                                                    <input type="text" class="form-control" id="reserve_start_time">
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-form-label col-md-1 col-sm-1 label-align" style="padding-bottom: 0; line-height: 35px">
                                               End Date &nbsp;
                                            </div>
                                            <div class="col-form-label col-md-2 col-sm-2 label-align" style="padding-bottom: 0; line-height: 35px">
                                                <div class="input-group date" id="datepicker4" style="margin-bottom: 5px;">
                                                    <input type="text" class="form-control" id="reserve_end_date" />
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-form-label col-md-2 col-sm-2 label-align text-right" style="padding-bottom: 0;">
                                                <div class="input-group date" id="datepicker6" style="padding-bottom: 0;">
                                                    <input type="text" class="form-control" id="reserve_end_time">
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>내용</th>
                                        <td colspan="5" style="text-align: unset">
                                            <textarea name="content" id="edit-content" class="summernote"></textarea>
                                        </td>
                                    </tr>
                                </table>
                                <div class="row col-md-12 col-sm-12">
                                    <div class="col-form-label col-md-6 col-sm-6 text-left">
                                        <button type="button" id="btn-new-article" class="btn btn-info btn-sm">새글등록</button>
                                    </div>
                                    <div class="col-form-label col-md-6 col-sm-6 text-right">
                                        <button type="button" id="btn-cancel" class="btn btn-secondary btn-sm">취소</button>
                                        <button type="button" id="btn-save-temp" class="btn btn-warning btn-sm">임시저장</button>
                                        <button type="button" id="btn-save" class="btn btn-primary btn-sm">등록</button>
                                        <input type="hidden" id="edit_id" />
                                        <input type="hidden" id="edit_mode" value="new" />
                                        <input type="hidden" id="is_ordered" value="false" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Switchery -->
    <link href="/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- bootstrap-datetimepicker -->
    <link href="/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
    <!-- include summernote css/js-->
    <link href="/vendors/summernote/summernote-lite.css" rel="stylesheet">
    <script src="/vendors/summernote/summernote-lite.js"></script>
    <style>
        .dataTables_length { display: none; }
        .btn-group.dt-buttons { position: absolute; top: -45px; right: 15px; }
        .btn-group.dt-buttons > a { display:inline-block; border: 1px solid #ced4da; }
        .dataTables_filter > label { display: none; }
        .paging_full_numbers { width: auto; }
        .paging_full_numbers { width: auto; }
        .switchery { width:32px;height:20px }
        .switchery>small { width:20px;height:20px }

        .info-table { margin-bottom: 10px; }
        .info-table th { color: #212529; text-align: center; vertical-align: middle; }
        .info-table td { text-align: center; vertical-align: middle; }
        .info-table td.number { text-align: right; }
        .note-editor { width: 100%; }

        .editor-box { margin: 30px 0; }
        .change_order { cursor: pointer; }
        #cur_mode { color : #0074cc; font-weight: bold; font-size: 18px; }
        .edit_mode { color : orange !important; }
        .bootstrap-datetimepicker-widget { width: auto; min-width: 0; height: auto; }
    </style>

    <script>
        const gameTypes = $.parseJSON('{!! json_encode(Helper::gameType()) !!}');
        const csCategories = $.parseJSON('{!! json_encode(Helper::csCategory()) !!}');
        const csStatus = $.parseJSON('{!! json_encode(Helper::csStatus()) !!}');

        $(document).ready(function() {
            let faq_table = $('#faq_table').DataTable({
                aaSorting: [],
                bSort: false,
                pageLength: 10,
                pagingType: 'full_numbers',
                language: {
                    "emptyTable": "데이터가 없습니다."
                },
            });

            $('#datepicker').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#datepicker2').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#datepicker3').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#datepicker4').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#datepicker5').datetimepicker({
                format: 'HH:mm:ss'
            });
            $('#datepicker6').datetimepicker({
                format: 'HH:mm:ss'
            });

            // check all
            $('#check_all').off('click').on('click', function() {
                $('.article_check').prop( 'checked', this.checked );
            });

            // date search make period
            $('.period-selector').off('click').on('click', function () {
                let period = $(this).attr('period');
                let target = $(this).attr('target');
                console.log('target', target);
                fillSearchDate(parseInt(period), 'search_start_date'+target, 'search_end_date'+target);
            });

            $('.period-reset').off('click').on('click', function () {
                $('#datepicker').data("DateTimePicker").clear();
                $('#datepicker2').data("DateTimePicker").clear();
            });

            $('#edit-content').summernote({
                placeholder: '',
                height: 300,
                minHeight: 300,
                maxHeight: null,
                lang: 'ko-KR',
                callbacks: {
                    onImageUpload:function(files, editor, welEditable){
                        for(var i = files.length - 1;i>=0;i--){
                            sendFile(files[i], this);
                        }
                    },
                },
                codeviewFilter: false,
                codeviewIframeFilter: true,
                spellCheck: false,
                tabDisable: false
            });

            // 검색
            $('#btn-search-log').off('click').on('click', function () {
                $('#search-errors').html('');

                let startDate = $('#search_start_date1').val();
                let endDate = $('#search_end_date1').val();
                let gameType = $('#game_type option:selected').val();
                let category = $('#category option:selected').val();
                let status = $('#status option:selected').val();
                let osType = $('#os_type option:selected').val();
                let adminName = $('#admin_name').val();

                let params = {
                    startDate: startDate,
                    endDate: endDate,
                    gameType: gameType,
                    category: category,
                    status: status,
                    osType: osType,
                    adminName: adminName,
                };
                loadList(params);
            });

            // upload
            let sendFile = function (file, el, welEditable) {
                let form_data = new FormData();
                form_data.append('file', file);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    data:form_data,
                    type:"POST",
                    url:'/management/upload',
                    cache:false,
                    contentType:false,
                    processData:false,
                    success:function(data){
                        console.log('data', data);
                        $(el).summernote('editor.insertImage', $.trim(data.fileUrl));
                    },
                    error: function(data) {
                        if (data.status === 419) {
                            alert('세션이 만료되었습니다.');
                            location.href = "/login";
                        }
                        console.log(data);
                    }
                });
            };

            let initLoad = function (ordered = 'false', period = null) {

                let startDate = -1;
                let endDate = -1;

                if (period !== null) {
                    fillSearchDate(period, 'search_start_date1', 'search_end_date1');
                    let initDate = new Date();
                    let ago = new Date();
                    ago.setDate(ago.getDate() - period);
                    startDate = getFormattedDate(ago.toDateString());
                    endDate = getFormattedDate(initDate.toDateString());
                }

                let initParams = {
                    startDate: startDate,
                    endDate: endDate,
                    gameType: '-1',
                    category: '-1',
                    status: '-1',
                    osType: '-1',
                    adminName: '',
                    ordered: ordered,
                };
                loadList(initParams);
            };

            // 쓰기
            let updateArticle = function(params) {
                if (params.mode === 'new') {
                    params.id = '-1';
                }

                let actionStr = "등록";
                if (params.from === "save_temp") {
                    actionStr = "임시 저장";
                } else if (params.from === "checkedShow" || params.from === "checkedHide") {
                    actionStr = "선택 항목을 일괄 변경";
                } else if (params.from === "checkedDelete") {
                    actionStr = "선택 항목을 일괄 삭제";
                }

                // check empty
                if (params.title.length === 0) {
                    alert('제목을 입력하세요');
                    $('#edit_title').focus();
                    return false;
                }
                if (params.gameType.length === 0) {
                    alert('게임을 선택하세요');
                    return false;
                }
                if (params.category.length === 0) {
                    alert('카테고리를 선택하세요');
                    return false;
                }
                if (params.osType.length === 0) {
                    alert('OS를 선택하세요');
                    return false;
                }
                if (params.status.length === 0) {
                    alert('상태를 선택하세요');
                    return false;
                }
                if (params.content.length === 0) {
                    alert('내용을 입력하세요');
                    $('#edit-content').focus();
                    return false;
                }

                if(!confirm( actionStr + '하시겠습니까?')) {
                    return false;
                }

                console.log('params', params);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '/management/updateFaq',
                    data: {
                        id: params.id,
                        title: params.title,
                        content: params.content,
                        gameType: params.gameType,
                        category: params.category,
                        status: params.status,
                        osType: params.osType,
                        isReserve: params.isReserve,
                        reserveStartDate: params.reserveStartDate,
                        reserveEndDate: params.reserveEndDate,
                        mode: params.mode,
                        isTempSave: params.isTempSave,
                        checked_ids: (params.checked_ids != undefined)? JSON.stringify(params.checked_ids) : -1,
                        from: params.from
                    },
                    dataType: 'json',
                    success: function (data) {
                        console.log(data);
                        if (data.error === true) {
                            console.log('checked error');
                            return;
                        }
                        $("#search-error-bag").hide();
                        let message = '';
                        $.each(data.messages, function(key, value) {
                            message += '\n' +value;
                        });

                        reload(message);
                    },
                    error: function(data) {
                        if (data.status === 419) {
                            alert('세션이 만료되었습니다.');
                            location.href = "/login";
                        }
                        let errors = $.parseJSON(data.responseText);
                        // console.log(errors);
                        $('#search-errors').html('');
                        $.each(errors.messages, function(key, value) {
                            $('#search-errors').append('<li>' + value + '</li>');
                        });
                        $("#search-error-bag").show();
                    }
                });
            };

            // checked article actions
            $('#btn-checked-show').off('click').on('click', function () {
                let article_ids = checkedIds();
                if (article_ids.length == 0) {
                    alert('선택 글이 없습니다.');
                    return false;
                }
                let params = {
                    id: -1,
                    gameType: -1,
                    category: -1,
                    status: -1,
                    osType: -1,
                    title: -1,
                    content: -1,
                    isReserve: -1,
                    checked_ids: article_ids,
                    from: 'checkedShow',
                };
                updateArticle(params);
            });

            $('#btn-checked-hide').off('click').on('click', function () {
                let article_ids = checkedIds();
                if (article_ids.length == 0) {
                    alert('선택 글이 없습니다.');
                    return false;
                }
                let params = {
                    id: -1,
                    gameType: -1,
                    category: -1,
                    status: -1,
                    osType: -1,
                    title: -1,
                    content: -1,
                    isReserve: -1,
                    checked_ids: article_ids,
                    from: 'checkedHide',
                };
                updateArticle(params);
            });

            $('#btn-checked-delete').off('click').on('click', function () {
                let article_ids = checkedIds();
                if (article_ids.length == 0) {
                    alert('선택 글이 없습니다.');
                    return false;
                }
                let params = {
                    id: -1,
                    gameType: -1,
                    category: -1,
                    status: -1,
                    osType: -1,
                    title: -1,
                    content: -1,
                    isReserve: -1,
                    checked_ids: article_ids,
                    from: 'checkedDelete',
                };
                updateArticle(params);
            });

            $('#btn-cancel').off('click').on('click', function () {
                if (confirm('작성 중인 내용이 있습니다.\n모두 초기화하시겠습니까?')) {
                    reset();
                }
            });

            $('#btn-save').off('click').on('click', function () {
                let params = {
                    id: $('#edit_id').val(),
                    title: $('#edit_title').val(),
                    content: $('#edit-content').val(),
                    gameType: $('#edit_game_type').val(),
                    category: $('#edit_category').val(),
                    status: $('#edit_status').val(),
                    osType: $('#edit_os_type').val(),
                    isReserve: $('input[name="is_reserve"]:checked').val(),
                    reserveStartDate: $('#reserve_start_date').val() + ' ' + $('#reserve_start_time').val(),
                    reserveEndDate: $('#reserve_end_date').val() + ' ' + $('#reserve_end_time').val(),
                    mode: $('#edit_mode').val(),
                    isTempSave: 'false',
                    from: 'faqEdit',
                };
                updateArticle(params);
            });

            $('#btn-save-temp').off('click').on('click', function () {
                let params = {
                    id: $('#edit_id').val(),
                    title: $('#edit_title').val(),
                    content: $('#edit-content').val(),
                    gameType: $('#edit_game_type').val(),
                    category: $('#edit_category').val(),
                    status: $('#edit_status').val(),
                    osType: $('#edit_os_type').val(),
                    isReserve: $('input[name="is_reserve"]:checked').val(),
                    reserveStartDate: $('#reserve_start_date').val() + ' ' + $('#reserve_start_time').val(),
                    reserveEndDate: $('#reserve_end_date').val() + ' ' + $('#reserve_end_time').val(),
                    mode: $('#edit_mode').val(),
                    isTempSave: 'true',
                    from: 'faqEdit',
                };
                updateArticle(params);
            });

            $('#btn-ordered').off('click').on('click', function () {
                initLoad('true', null);
            });

            $('#btn-preview').off('click').on('click', function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'GET',
                    url: '/management/previewFaq',
                    data: {
                        from: 'faq'
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        if (data.error === true) {
                            console.log('checked error');
                            return;
                        }
                        $("#search-error-bag").hide();

                        showPreview('faq', '/preview/faq/FaqList.html');
                    },
                    error: function(data) {
                        if (data.status === 419) {
                            alert('세션이 만료되었습니다.');
                            location.href = "/login";
                        }
                        let errors = $.parseJSON(data.responseText);
                        // console.log(errors);
                        $('#search-errors').html('');
                        $.each(errors.messages, function(key, value) {
                            $('#search-errors').append('<li>' + value + '</li>');
                        });
                        $("#search-error-bag").show();
                    }
                });
            });

            $('#btn-publish').off('click').on('click', function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'GET',
                    url: '/management/publishFaq',
                    data: {
                        from: 'faq'
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        if (data.error === true) {
                            console.log('checked error');
                            return;
                        }
                        $("#search-error-bag").hide();

                        showPublish('faq', '/publish/faq/FaqList.html');
                    },
                    error: function(data) {
                        if (data.status === 419) {
                            alert('세션이 만료되었습니다.');
                            location.href = "/login";
                        }
                        let errors = $.parseJSON(data.responseText);
                        // console.log(errors);
                        $('#search-errors').html('');
                        $.each(errors.messages, function(key, value) {
                            $('#search-errors').append('<li>' + value + '</li>');
                        });
                        $("#search-error-bag").show();
                    }
                });
            });

            $('#btn-new-article').off('click').on('click', function () {
                changeMode();
                $('#cur_mode').removeClass('edit_mode');
                $('#cur_mode').text('등록');

           });

            // initial load 1 month
            initLoad();
        });

        // listing
        function loadList(params) {
            console.log('loadList', params);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            if (params.ordered === 'true') {
                $('#is_ordered').val('true');
                $('#sort-text').text('노출순서 정렬');
            } else {
                $('#is_ordered').val('false');
                $('#sort-text').text('등록일시 정렬');
            }

            $.ajax({
                type: 'POST',
                url: '/management/listFaq',
                data: {
                    startDate: params.startDate,
                    endDate: params.endDate,
                    gameType: params.gameType,
                    category: params.category,
                    status: params.status,
                    osType: params.osType,
                    adminName: params.adminName,
                    ordered: params.ordered,
                    from: 'faq'
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    if (data.error === true) {
                        console.log('checked error');
                        return;
                    }
                    $("#search-error-bag").hide();

                    drawTable(data);
                },
                error: function(data) {
                    if (data.status === 419) {
                        alert('세션이 만료되었습니다.');
                        location.href = "/login";
                    }
                    let errors = $.parseJSON(data.responseText);
                    // console.log(errors);
                    $('#search-errors').html('');
                    $.each(errors.messages, function(key, value) {
                        $('#search-errors').append('<li>' + value + '</li>');
                    });
                    $("#search-error-bag").show();
                }
            });
        }

        // show article edit panel
        $(document).on('click', '.btn-edit-article', function () {
            changeMode();

            let data = $(this).data();
            $('#cur_mode').addClass('edit_mode');
            $('#cur_mode').text('수정');
            $('#cur_article').text('현재 글번호 : ' + data.id);
            $('#edit_mode').val('edit');

            $('#tr_'+data.id).children('td, th').css('background', '#FFB6C1');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '/management/getFaq',
                data: {
                    id: data.id,
                    from: 'faq',
                },
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    if (data.error === true) {
                        console.log('checked error');
                        return;
                    }
                    $("#search-error-bag").hide();

                    // update edit panel
                    let article = data.faqArticle;
                    $('#edit_id').val(article.id);
                    $('#edit_title').val(article.title);
                    $('#edit_game_type').val(article.game_type);
                    $('#edit_category').val(article.category);
                    $('#edit_os_type').val(article.os.toLowerCase());
                    $('#edit_status').val(article.status);
                    if( article.reserve_start_date != null ) {
                        let edit_start_date = article.reserve_start_date.substr(0, 10);
                        let edit_start_time = article.reserve_start_date.substr(11, edit_start_date.length);
                        $('#reserve_start_date').val(edit_start_date);
                        $('#reserve_start_time').val(edit_start_time);
                    }
                    if( article.reserve_end_date != null ) {
                        let edit_end_date = article.reserve_end_date.substr(0, 10);
                        let edit_end_time = article.reserve_end_date.substr(11, edit_end_date.length);
                        $('#reserve_end_date').val(edit_end_date);
                        $('#reserve_end_time').val(edit_end_time);
                    }
                    $("#edit-content").summernote('code', article.content);

                },
                error: function(data) {
                    if (data.status === 419) {
                        alert('세션이 만료되었습니다.');
                        location.href = "/login";
                    }
                    let errors = $.parseJSON(data.responseText);
                    // console.log(errors);
                    $('#search-errors').html('');
                    $.each(errors.messages, function(key, value) {
                        $('#search-errors').append('<li>' + value + '</li>');
                    });
                    $("#search-error-bag").show();
                }
            });
        });

        // show article delete
        $(document).on('click', '.btn-delete-article', function () {
            // make params
            let data = $(this).data();

            if (!confirm('해당 글을 삭제 하시겠습니까?')) {
                return false;
            }

            // send ajax
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '/management/updateFaq',
                data: {
                    id: data.id,
                    title: -1,
                    content: -1,
                    gameType: -1,
                    category: -1,
                    status: -1,
                    osType: -1,
                    isReserve: -1,
                    reserveStartDate: -1,
                    reserveEndDate: -1,
                    mode: -1,
                    isTempSave: -1,
                    from: 'faqDelete',
                },
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    if (data.error === true) {
                        console.log('checked error');
                        return;
                    }
                    $("#search-error-bag").hide();
                    let message = '';
                    $.each(data.messages, function(key, value) {
                        message += '\n' +value;
                    });

                    reload(message);
                },
                error: function(data) {
                    if (data.status === 419) {
                        alert('세션이 만료되었습니다.');
                        location.href = "/login";
                    }
                    let errors = $.parseJSON(data.responseText);
                    // console.log(errors);
                    $('#search-errors').html('');
                    $.each(errors.messages, function(key, value) {
                        $('#search-errors').append('<li>' + value + '</li>');
                    });
                    $("#search-error-bag").show();
                }
            });
        });

        // change order
        $(document).on('click', '.change_order', function () {

            // check date search
            let startDate = $('#search_start_date1').val();
            let endDate = $('#search_end_date1').val();
            let isOrdered = $('#is_ordered').val();
            if (startDate.length > 0 || endDate.length > 0) {
                alert('날짜 검색 상태에서는 노출순서 변경이 불가능합니다.\n[날짜 초기화] 버튼을 이용하여 다시 검색후 변경해주세요.');
                return false;
            }
            if (isOrdered !== "true") {
                alert('노출순서 보기 상태에서만 가능합니다..\n[노출순서보기] 버튼을 이용 후 변경해주세요.');
                return false;
            }

            let data = $(this).data();
            console.log(data, data);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '/management/updateFaq',
                data: {
                    id: data.id,
                    order: data.order,
                    direction: data.direction,
                    gameType: -1,
                    category: -1,
                    status: -1,
                    osType: -1,
                    title: 'order',
                    content: 'order',
                    startDate: -1,
                    endDate: -1,
                    isReserve: -1,
                    from: 'faqOrder',
                },
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    if (data.error === true) {
                        console.log('checked error');
                        return;
                    }
                    $("#search-error-bag").hide();

                    let message = '';
                    $.each(data.messages, function(key, value) {
                        message += '\n' +value;
                    });

                    reload(message)
                },
                error: function (data) {
                    let errors = $.parseJSON(data.responseText);
                    // console.log(errors);
                    $('#search-errors').html('');
                    $.each(errors.messages, function (key, value) {
                        $('#search-errors').append('<li>' + value + '</li>');
                    });
                    $("#search-error-bag").show();
                }
            });
        });

        function drawTable(data) {
            let faqList = data.faqList;
            // load list
            let faq_table = $('#faq_table').DataTable();
            faq_table.clear();
            if (faqList.length > 0) {
                let no = 0;
                $(faqList).each(function(index, faq) {
                    // console.log('faq', faq);
                    let tr = $('<tr id="tr_'+faq.id+'">' +
                        '<td><input type="checkbox" class="article_check" name="faq_id" value="'+ faq.id +'" /></td>' +
                        '<td>' + (++no) + '</td>' +
                        '<td><a class="change_order" data-direction="up" data-id="'+ faq.id +'" data-order="'+ faq.order +'"><i class="fa fa-arrow-up"></i></a></td>' +
                        '<td><a class="change_order" data-direction="down" data-id="'+ faq.id +'" data-order="'+ faq.order +'"><i class="fa fa-arrow-down"></i></a></td>' +
                        '<td>' + gameTypes[faq.game_type].name + '</td>' +
                        '<td>' + csCategories[faq.category].name + '</td>' +
                        '<td>' + faq.title + '</td>' +
                        '<td>' + faq.os.toUpperCase() + '</td>' +
                        '<td>' + csStatus[faq.status].name + '</td>' +
                        '<td>' + faq.admin_name + '</td>' +
                        '<td>' + getFormattedDatetime(faq.create_date) + '</td>' +
                        '<td><button class="btn-sm btn-success btn-edit-article" data-id="'+faq.id+'">수정</button> '+
                        '<button class="btn-sm btn-danger btn-delete-article" data-id="'+faq.id+'">삭제</button></td>' +
                        '</tr>');
                    faq_table.row.add(tr);
                });
            }
            faq_table.draw();
        }

        function changeMode() {
            $('#faq_table tr').children('td, th').css('background', '#FFF');
            $('#cur_article').text(' ');
            $('#edit_mode').val('new');
            reset();
        }

        function reset() {
            $('#edit_title').val('');
            $('#edit_game_type option:eq(0)').attr('selected', 'selected');
            $('#edit_category option:eq(0)').attr('selected', 'selected');
            $('#edit_os_type option:eq(0)').attr('selected', 'selected');
            $('#edit_status option:eq(0)').attr('selected', 'selected');
            $('input[name="is_reserve"]').eq(0).iCheck('check');
            $('#edit_start_date').val('');
            $('#edit_start_time').val('');
            $('#edit_end_date').val('');
            $('#edit_end_time').val('');
            $('#edit-content').val('');
            $("#edit-content").summernote("reset");
        }

        function checkedIds()
        {
            let ids = $("#faq_table input:checkbox:checked").map(function(){
                if ($(this).val() !== 'all') {
                    return $(this).val();
                }
            }).get();
            return ids;
        }

        function reload(message) {
            alert(message);
            document.location.reload();
        }
    </script>

    <!-- Datatables -->
    <script src="/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="/vendors/jszip/dist/jszip.min.js"></script>
    <script src="/vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="/vendors/pdfmake/build/vfs_fonts.js"></script>

    <!-- Switchery -->
    <script src="/vendors/switchery/dist/switchery.min.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="/vendors/moment/min/moment.min.js"></script>
    <script src="/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap-datetimepicker -->
    <script src="/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
@endsection
