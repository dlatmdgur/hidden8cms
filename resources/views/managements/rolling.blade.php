@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('managements.notice') }}">운영관리</a></li>
                <li class="breadcrumb-item">롤링공지 설정</li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel" style="overflow-x: auto; overflow-y: hidden;">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb p-0">
                                <h2><b>롤링공지 목록</b></h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col p-0">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <form name="frmGet" method="post" action="/management/listRolling" onsubmit="return false;" method-transfer="async" data-autoload="1" class="m-0">
                                            <table class="table table-sm table-striped table-bordered table-hover mb-0">
                                                <thead class="thead-light text-center align-top">
                                                <tr>
                                                    <th>No.</th>
                                                    <th>공지내용</th>
                                                    <th>시작시간</th>
                                                    <th>발송횟수</th>
                                                    <th>발송간격</th>
                                                    <th>진행상태</th>
                                                    <th>등록일</th>
                                                    <th>-</th>
                                                </tr>
                                                </thead>
                                                <tbody class="text-center text-nowrap align-middle client">
                                                <tr>
                                                    <td colspan="999" class="text-center p-3"><b><i>DATA LOADING...</i></b></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="x_panel" style="overflow-x: auto; overflow-y: hidden;">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb p-0">
                                <h2><b>공지 등록</b></h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col p-0">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <form name="frmSet" method="post" action="/management/setRolling" onsubmit="return false;" method-transfer="async" data-autoload="1" class="m-0">
                                            <table class="table table-sm table-hover mb-0">
                                                <tbody class="text-center text-nowrap align-middle client">
                                                <tr>
                                                    <th class="bg-light">발송횟수</th>
                                                    <td>
                                                        <div class="col-12 col-xl-5">
                                                            <input type="number" name="noti_count" class="form-control" value="1">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">발송간격(분)</th>
                                                    <td>
                                                        <div class="col-12 col-xl-5">
                                                            <input type="number" name="noti_interval" class="form-control" value="1">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">발송내용</th>
                                                    <td>
                                                        <div class="col-12 col-xl-10">
                                                            <input type="text" name="message" class="form-control">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">시작시간</th>
                                                    <td>
                                                        <div class="row ml-1">
                                                            <div class="col-12 col-xl-4">
                                                                <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}">
                                                            </div>
                                                            <div class="col-12 col-xl-4">
                                                                <input type="time" name="start_time" class="form-control" value="{{ date('H:i') }}">
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <button class="btn btn-sm btn-primary">등록</button>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 삭제 -->
    <div id="drop" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form name="frmDrop" method="post" action="{{ Route('managements.dropRolling') }}" onsubmit="return false;" method-transfer="async" class="m-0">
                    <div class="modal-header"><h5 class="modal-title">롤링공지 삭제</h5></div>
                    <input type="hidden" name="idx">
                    <div class="modal-body was-validated">
                        <p>삭제 하시겠습니까?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" aria-label="drop">삭제</button>
                        <button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">취소</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="/js/apis.js"></script>
    <script>
        $(function() {
            //버전정보
            let data = [];
            const cols = ['no', 'message', 'start_datetime', 'noti_count', 'noti_interval', 'status', 'created_datetime', 'group1'];

            $('form[name=frmGet]').submit();

            //품 결과 처리
            $('form[name="frmGet"]').on('callback', function(e, d) {
                if(d.result !== 0) return alert(d.msg);
                data = d.data;

                let node = $(e.currentTarget).find('tbody');
                node.html('');

                if(data.length == 0) node.append('<tr class="text-center"><td colspan="999">등록된 공지가 없습니다.</td></tr>');
                else {
                    for(let i in data) {
                        let tds = [];
                        for(let j in cols) {
                            switch(cols[j]) {
                                case 'group1':
                                    tds.push('<td>' +
                                        '<button type="button" class="btn btn-sm btn-danger" data-i="' + i + '" data-toggle="modal" data-size="modal-xl" data-target="#drop">삭제</button>' +
                                        '</td>');
                                    break;
                                default:
                                    tds.push('<td>'+ data[i][cols[j]] +'</td>');
                                    break;
                            }
                        }
                        node.append('<tr class="text-center">' + tds.join('') + '</tr>');
                    }
                }
            });

            $('button.btn-primary').unbind('click').bind('click', function() {

            });

            $('#drop').on('show.bs.modal', function(e) {
                let t = $(e.relatedTarget).data('i');
                let d = data[t];
                let f = $(e.currentTarget).find('form');
                let s = t == undefined;

                f.find('[name=idx]').val(s ? '' : d['idx']);
            });

            //추가, 삭제 콜백 처리
            $('form[name=frmSet], form[name=frmDrop]').on('callback', function(e, d) {
                if(d.messages != undefined && d.messages != '') alert(d.messages);
                if(d.result != 1) return false;
                document.location.reload();
            });
        });
    </script>
@endsection
