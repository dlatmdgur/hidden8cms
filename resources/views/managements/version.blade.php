@extends('layouts.mainlayout')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('managements.version') }}">서버설정</a></li>
                <li class="breadcrumb-item">버전 설정</li>
            </ol>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-tabs">
                        <li class="nav-item" data-tab="client">
                            <a class="nav-link" role="button"><strong>클라이언트 버전</strong></a>
                        </li>
                        <li class="nav-item" data-tab="server">
                            <a class="nav-link" role="button"><strong>서버 버전</strong></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row" menu-tab="client">
                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel" style="overflow-x: auto; overflow-y: hidden;">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb p-0">
                                <h2><b>클라이언트 버전</b></h2>
                                <div class="pull-right pr-0">
                                    <button class="btn btn-info" data-toggle="modal" data-size="modal-xl" data-target="#modify_client"><b>추가</b></button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col p-0">
                                <div class="card">
                                    <div class="card-header">
                                    </div>
                                    <div class="card-body p-0">
                                        <form name="frmClientGet" method="post" action="/management/listVersion" onsubmit="return false;" method-transfer="async" data-autoload="1" class="m-0">
                                        <input type="hidden" name="type" value="client">
                                        <table class="table table-sm table-striped table-bordered table-hover mb-0">
                                            <thead class="thead-dark text-center align-top">
                                            <tr>
                                                <th>버전</th>
                                                <th>최근수정날짜</th>
                                                <th>-</th>
                                            </tr>
                                            </thead>
                                            <tbody class="text-center text-nowrap align-middle client">
                                                <tr>
                                                    <td colspan="999" class="text-center p-3"><b><i>VERSION DATA LOADING...</i></b></td>
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
            <div class="row" menu-tab="server" style="display: none;">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel" style="overflow-x: auto; overflow-y: hidden;">
                        <div class="row x_title">
                            <div class="col-lg-12 margin-tb p-0">
                                <h2><b>서버 버전</b></h2>
                                <div class="pull-right pr-0">
                                    <button class="btn btn-info" data-toggle="modal" data-size="modal-xl" data-target="#modify_server"><b>추가</b></button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col p-0" style="overflow-y:hidden; overflow-x: auto;">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <form name="frmServerGet" method="post" action="/management/listVersion" onsubmit="return false;" method-transfer="async" data-autoload="1" class="m-0">
                                            <input type="hidden" name="type" value="server">
                                            <table class="table table-sm table-striped table-bordered table-hover mb-0">
                                                <thead class="thead-dark text-center align-top">
                                                <tr style="white-space:nowrap; word-break: break-all;">
                                                    <th>버전</th>
                                                    <th>환경</th>
                                                    <th>CDN</th>
                                                    <th>웹월드</th>
                                                    <th>슬롯서버</th>
                                                    <th>슬롯로비</th>
                                                    <th>토너먼트로비</th>
                                                    <th>http로비</th>
                                                    <th>서버상태</th>
                                                    <th>서버공지</th>
                                                    <th>-</th>
                                                </tr>
                                                </thead>
                                                <tbody class="text-center text-nowrap align-middle server">
                                                <tr>
                                                    <td colspan="999" class="text-center p-3"><b><i>VERSION DATA LOADING...</i></b></td>
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
    <!-- 추가/변경 -->
    <div id="modify_client" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form name="frmSetClient" method="post" action="{{ Route('managements.setVersion') }}" onsubmit="return false;" method-transfer="async" class="m-0">
                <input type="hidden" name="type" value="client">
                <input type="hidden" name="old_version">
                    <div class="modal-header"><h5 class="modal-title">클라이언트 버전 추가/변경</h5></div>
                    <div class="modal-body was-validated">
                        <label class="mb-0" for="version"><strong>버전</strong></label>
                        <div class="form-group">
                            <input type="text" name="version" value="" placeholder="?.?.?.?" class="form-control" required />
                            <div class="valid-feedback">입력 완료</div>
                            <div class="invalid-feedback">버전 값을 입력해야 합니다.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" aria-label="modify_client">추가(변경)</button>
                        <button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">취소</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="modify_server" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form name="frmSetServer" method="post" action="{{ Route('managements.setVersion') }}" onsubmit="return false;" method-transfer="async" class="m-0">
                <input type="hidden" name="type" value="server">
                <input type="hidden" name="idx" value="">
                    <div class="modal-header"><h5 class="modal-title">서버 버전 추가/변경</h5></div>
                    <div class="modal-body was-validated">
                        <label class="mb-0" for="version"><strong>버전</strong></label>
                        <div class="form-group">
                            <input type="text" name="version" value="" placeholder="?.?.?.?" class="form-control" required />
                            <div class="valid-feedback">입력 완료</div>
                            <div class="invalid-feedback">버전 값을 입력해야 합니다.</div>
                        </div>
                        {{--                        <hr class="mt-3" />--}}
                        <label class="mb-0" for="desc"><strong>환경</strong></label>
                        <div class="form-group">
                            <input type="text" name="desc" value="" placeholder="환경" class="form-control" />
                        </div>
                        <label class="mb-0" for="cdn"><strong>CDN</strong></label>
                        <div class="form-group">
                            <input type="text" name="cdn" value="" placeholder="CDN 주소" class="form-control" />
                        </div>
                        <label class="mb-0" for="web_world"><strong>웹월드</strong></label>
                        <div class="form-group">
                            <input type="text" name="web_world" value="" placeholder="웹월드 주소" class="form-control" />
                        </div>
                        <label class="mb-0" for="slot_server"><strong>슬롯서버</strong></label>
                        <div class="form-group">
                            <input type="text" name="slot_server" value="" placeholder="슬롯서버 주소" class="form-control" />
                        </div>
                        <label class="mb-0" for="slot_lobby"><strong>슬롯로비</strong></label>
                        <div class="form-group">
                            <input type="text" name="slot_lobby" value="" placeholder="슬롯로비 주소" class="form-control" />
                        </div>
                        <label class="mb-0" for="tournament_lobby"><strong>토너먼트로비</strong></label>
                        <div class="form-group">
                            <input type="text" name="tournament_lobby" value="" placeholder="토너먼트로비 주소" class="form-control" />
                        </div>
                        <label class="mb-0" for="http_lobby"><strong>http로비</strong></label>
                        <div class="form-group">
                            <input type="text" name="http_lobby" value="" placeholder="http로비 주소" class="form-control" />
                        </div>
                        <label class="mb-0" for="server_status"><strong>서버상태</strong></label>
                        <div class="form-group">
                            <select name="server_status" class="form-control custom-select">
                                <option value="0">정상</option>
                                <option value="1">점검중</option>
                            </select>
                        </div>
                        <label class="mb-0" for="server_notice"><strong>서버공지</strong></label>
                        <div class="form-group">
                            <textarea name="server_notice" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" aria-label="modify_server">추가(변경)</button>
                        <button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">취소</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 삭제 -->
    <div id="drop_client" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form name="frmDropClient" method="post" action="{{ Route('managements.dropVersion') }}" onsubmit="return false;" method-transfer="async" class="m-0">
                    <div class="modal-header"><h5 class="modal-title">클라이언트 버전 삭제</h5></div>
                    <input type="hidden" name="type" value="client">
                    <input type="hidden" name="version">
                    <div class="modal-body was-validated">
                        <p>삭제 하시겠습니까?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" aria-label="drop_client">삭제</button>
                        <button type="cancel" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">취소</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="drop_server" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form name="frmDropServer" method="post" action="{{ Route('managements.dropVersion') }}" onsubmit="return false;" method-transfer="async" class="m-0">
                    <div class="modal-header"><h5 class="modal-title">서버 버전 삭제</h5></div>
                    <input type="hidden" name="type" value="server">
                    <input type="hidden" name="idx">
                    <div class="modal-body was-validated">
                        <p>삭제 하시겠습니까?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" aria-label="drop_server">삭제</button>
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
    const cols_client = ['version', 'update_date', 'group1'];
    const cols_server = [
        'idx', 'version', 'desc', 'cdn', 'web_world'
        , 'slot_server', 'slot_lobby', 'tournament_lobby', 'http_lobby', 'server_status', 'server_notice', 'group1'
    ];

    $('li[data-tab=client]').click();

    //품 결과 처리
    $('form[name="frmClientGet"]').on('callback', function(e, d) {
        if(d.result !== 0) return alert(d.msg);
        data = d.data;

        let node = $(e.currentTarget).find('tbody.client');
        node.html('');

        let cols = cols_client;
        for(let i in data) {
            let tds = [];
            for(let j in cols) {
                switch(cols[j]) {
                    case 'group1':
                        tds.push('<td><div class="btn-group">' +
                            '<button type="button" class="btn btn-sm btn-primary" data-i="' + i + '" data-toggle="modal" data-size="modal-xl" data-target="#modify_client">변경</button>' +
                            '<button type="button" class="btn btn-sm btn-danger" data-i="' + i + '" data-toggle="modal" data-size="modal-xl" data-target="#drop_client">삭제</button>' +
                            '</div></td>');
                        break;
                    default:
                        tds.push('<td>'+ data[i][cols[j]] +'</td>');
                        break;
                }
            }
            node.append('<tr class="text-center">' + tds.join('') + '</tr>');
        }
    });
    $('form[name="frmServerGet"]').on('callback', function(e, d) {
        if(d.result !== 0) return alert(d.msg);
        data = d.data;
        let node = $(e.currentTarget).find('tbody.server');
        node.html('');

        let cols = cols_server;
        for(let i in data) {
            let tds = [];
            for(let j in cols) {
                switch(cols[j]) {
                    case 'group1':
                        tds.push('<td><div class="btn-group">' +
                            '<button type="button" class="btn btn-sm btn-primary" data-i="' + i + '" data-toggle="modal" data-size="modal-xl" data-target="#modify_server">변경</button>' +
                            '<button type="button" class="btn btn-sm btn-danger" data-i="' + i + '" data-toggle="modal" data-size="modal-xl" data-target="#drop_server">삭제</button>' +
                            '</div></td>');
                        break;
                    case 'idx':
                        break;
                    case 'server_status':
                        tds.push('<td> '+ (data[i][cols[j]] == 0 ? '정상' : '점검중') + '</td>');
                        break;
                    case 'server_notice':
                        tds.push('<td> '+ (data[i][cols[j]] == null ? '' : data[i][cols[j]]) + '</td>');
                        break;
                    default:
                        tds.push('<td>'+ data[i][cols[j]] +'</td>');
                        break;
                }
            }
            node.append('<tr class="text-center">' + tds.join('') + '</tr>');
        }
    });

    //변경 버튼 눌렀을때 이벤트
    $('#modify_client').on('show.bs.modal', function(e) {
        let t = $(e.relatedTarget).data('i');
        let d = data[t];
        let f = $(e.currentTarget).find('form');
        let s = t == undefined;

        let cols = cols_client;
        for(let i in cols) {
            switch(cols[i]) {
                case 'version':
                    f.find('[name='+cols[i]+']').val(s ? '' : d[cols[i]]);
                    f.find('[name=old_version]').val(s ? '' : d[cols[i]]);
                default:
                    f.find('[name='+cols[i]+']').val(s ? '' : d[cols[i]]);
                    break;
            }
        }
    });
    $('#modify_server').on('show.bs.modal', function(e) {
        let t = $(e.relatedTarget).data('i');
        let d = data[t];
        let f = $(e.currentTarget).find('form');
        let s = t == undefined;

        let cols = cols_server;
        for(let i in cols) {
            switch(cols[i]) {
                case 'server_status':
                    if(!s) f.find('[name='+cols[i]+']').find('option').eq(d[cols[i]]).prop('selected', true);
                    break;
                default:
                    f.find('[name='+cols[i]+']').val(s ? '' : d[cols[i]]);
                    break;
            }
        }
    });
    //삭제 버튼 눌렀을때 이벤트
    $('#drop_client').on('show.bs.modal', function(e) {
        let t = $(e.relatedTarget).data('i');
        let d = data[t];
        let f = $(e.currentTarget).find('form');
        let s = t == undefined;

        f.find('[name=version]').val(s ? '' : d['version']);
    });
    $('#drop_server').on('show.bs.modal', function(e) {
        let t = $(e.relatedTarget).data('i');
        let d = data[t];
        let f = $(e.currentTarget).find('form');
        let s = t == undefined;

        f.find('[name=idx]').val(s ? '' : d['idx']);
    });

    //추가, 변경, 삭제 콜백 처리
    $('form[name=frmSetClient], form[name=frmSetServer], form[name=frmDropClient], form[name=frmDropServer]').on('callback', function(e, d) {
        if(d.messages != undefined && d.messages != '') alert(d.messages);
        if(d.result != 1) return false;
        $('button[type=cancel]').click();
        if(this.name.indexOf('Client') >= 0) $('li[data-tab=client]').click();
        else $('li[data-tab=server]').click();
    });
});
</script>
@endsection
