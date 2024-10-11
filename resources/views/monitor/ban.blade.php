@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('monitor.abuse') }}">모니터링</a></li>
                <li class="breadcrumb-item">일괄변경</li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h2>일괄변경</h2>
                                </div>
                            </div>
                        </div>


                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif



                        <div class="row">
                            <div class="col-md-12">
                                <form id="form" method="POST" enctype="multipart/form-data" action="operation/excelUpload">
                                    <div class="input-group col-md-6">
                                        <div class="custom-file">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="file" class="custom-file-input" id="excel_file" name="excel_file">
                                            <label class="custom-file-label" for="excel_file">Choose file</label>
                                        </div>
                                    </div>
                                    <div class="input-group col-md-3">
                                        <input type="button" name="excel_upload" id="excel_upload" class="btn btn-primary" value="Upload">
                                    </div>
                                    <div class="input-group col-md-3">
                                        <span style="font-size: 16px; line-height: 35px;">{{ $template }} </span>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <table class="table table-bordered info-table">
                            <tr>
                                <th>변경 대상</th>
                                <td colspan="5"><textarea class="form-control" id="ban_users" name="ban_users" style="height: 300px;" readonly></textarea>
                                    <textarea id="ban_user_json" style="display: none"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <th>변경 상태</th>
                                <td style="text-align: left;">
                                    <select class="form-control" id="userState" name="userState">
                                        <option value="">상태 선택</option>
                                        <option value="1">정상</option>
                                        <option value="3">정지</option>
                                        <option value="4">CS처리중</option>
                                    </select>
                                </td>
                                <th>처리사유 선택 (1)</th>
                                <td style="text-align: left;">
                                    <select class="form-control" id="log_type" name="log_type">
                                        <option value="">처리사유 선택</option>
                                        <option value="abuze">어뷰징</option>
                                        <option value="makeUp">짜고치기</option>
                                        <option value="violation">운영정책위반</option>
                                        <option value="illegalUse">도용의심</option>
                                        <option value="correction">오처리</option>
                                        <option value="admin">기타 - 운영자처리</option>
                                        <option value="test">테스트</option>
                                    </select>
                                </td>
                                <th>처리사유 입력 (2)</th>
                                <td>
                                    <input type="text" class="form-control" id="log_reason" name="log_reason" value="" placeholder="처리사유 입력"/>
                                    <input type="hidden" id="user_seq" value="" />
                                    <input type="hidden" id="origin_gem" value="" />
                                    <input type="hidden" id="origin_event_gem" value="" />
                                </td>
                            </tr>
                        </table>
                        <div style="border-left:0; border-right:0; border-bottom:0; text-align: right;"><button type="button" id="btn-ban" class="btn btn-primary">변경</button></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Switchery -->
    <link href="/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <style>
        .dataTables_length { display: none; }
        .btn-group.dt-buttons { position: absolute; top: -45px; right: 15px; }
        .btn-group.dt-buttons > a { display:inline-block; border: 1px solid #ced4da; }
        .dataTables_filter > label { width: 100%; text-align: left; }
        .dataTables_filter > label > input { display: inline-block; width: 180px; margin-left: 5px; }
        .paging_full_numbers { width: auto; }
        .switchery { width:32px;height:20px }
        .switchery>small { width:20px;height:20px }
        .custom-file {font-size: 16px;}

        .info-table th { text-align: center; vertical-align: middle; }
        .info-table td { text-align: center; vertical-align: middle; }
        .info-table td.number { text-align: right; }
    </style>

    <script>
        $(document).ready(function () {
            $('#excel_file').on('change',function(){
                //get the file name
                var fileName = $(this).val();
                $(this).next('.custom-file-label').html(fileName);
            });

            $('#excel_upload').on('click', function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                let file_data = $("#excel_file").prop("files")[0];
                let form_data = new FormData();
                form_data.append("excel_file", file_data);

                $.ajax({
                    type: 'POST',
                    url: '/operation/excelUpload',
                    data: form_data,
                    // dataType: 'script',
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data){
                        console.log('data', data);

                        let users = '';
                        let userSeqs = '';
                        let userNicknames = '';
                        $(data.resultSet).each(function(index, user) {
                            users += ' userSeq : ' + user.user_seq + ' nickname : ' + user.nickname + '\n';
                            userSeqs += user.user_seq + ',';
                            userNicknames += user.nickname + ',';
                        });
                        $('#ban_users').val(users);
                        $('#ban_user_json').val(JSON.stringify(data.resultSet));
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

            $('#present_type').on('change', function() {
                let limitTypes = [2003, 2012, 2014, 2019];
                let presentType = $('#present_type option:selected').val();
                // 아이템별 지급 수량 제한
                if (limitTypes.indexOf(parseInt(presentType)) >= 0) {
                    $('#present_amount').val(1);
                }
            });

            // action : 제재
            $('#btn-ban').off('click').on('click', function() {
                // check common
                let bandUsers = $('#ban_users').val();
                let userJson = $('#ban_user_json').val();
                let userState = $('#userState option:selected').val();
                let logType = $('#log_type option:selected').val();
                let logReason = $('#log_reason').val();
                let actionType = 'ban';

                if (bandUsers.length === 0) {
                    alert('제재 대상을 입력하세요.');
                    return false;
                }
                if (userState.length === 0) {
                    alert('변경할 상태를 선택하세요');
                    return false;
                }
                if (logType.length === 0) {
                    alert('처리사유(1)를 선택하세요');
                    return false;
                }
                if (logReason.length === 0) {
                    alert('처리사유(2)를 입력하세요.');
                    return false;
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '/monitor/editMassive',
                    data: {
                        userJson: userJson,
                        userState: userState,
                        actionType: actionType,
                        logType: logType,
                        logReason: logReason,
                        from: 'ban'
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        if (data.error === true) {
                            console.log('checked error');
                            return;
                        }

                        $("#search-error-bag").hide();

                        alert('일괄변경이 완료되었습니다.');
                        reset();
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
        });

        function reset() {
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

@endsection
