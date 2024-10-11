<?php
$authUserNo = 0;		// 고객번호
$authUserName = "";		// 닉네임
$authUserID = "";		// 회원정보

$authUserNo = isset($_GET['authUserNo'])? $_GET['authUserNo'] : (isset($_POST['authUserNo'])? $_POST['authUserNo'] : "");
$authUserName = isset($_GET['authUserName'])? $_GET['authUserName'] : (isset($_POST['authUserName'])? $_POST['authUserName'] : "");
$authUserID = isset($_GET['authUserID'])? $_GET['authUserID'] : (isset($_POST['authUserID'])? $_POST['authUserID'] : "");

$userAgent = $_SERVER['HTTP_USER_AGENT'];
$reg = preg_match('/\bZFBrowser\b/i',$userAgent,$matches);
$isOnline = !empty($matches);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>SuperWinGame Notice</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width" />
    <meta http-equiv="Expires" content="Mon, 22 Jul 2002 11:12:01 GMT" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Cache-Control" content="No-Cache" />

    <link type="text/css" href="./assets/css/style.css?v=20211021" rel="stylesheet" />
    <script src="./assets/js/jquery.2.2.4.min.js"></script>
    <script src="./assets/js/common.js?v=20200818"></script>
</head>
<body>
<!-- wrap -->
<div id="page">
    <section id="att_bg">
        <div id="btn_1" class="basic_padding cs_name"><b>공지사항</b><div class="cs_arrow"></div></div>
        <ul id="nav_notice" class="cs_list">
        </ul>

        <div id="btn_2" class="basic_padding cs_name"><b>FAQ</b><div class="cs_arrow"></div></div>
        <ul id="nav_faq" class="cs_list">
        </ul>
        <div class="basic_padding">
            <b>문의하기</b>&nbsp;&nbsp;
            <?php if ($isOnline) { ?>
                <a href="javascript:showInfo('qna');">jnc@jncgift.co.kr</a>
            <?php } else { ?>
                <a href="mailto:jnc@jncgift.co.kr?subject=SuperWinGame 게임관련 문의&body=고객번호%20%3A<?=$authUserNo?>%0D%0A닉네임%20%3A<?=$authUserName?>%0D%0A내용%20%3A">jnc@jncgift.co.kr</a>
            <?php } ?>
        </div>
        <?php /* dev 까지만 */ ?>
        <div class="basic_padding" style="position: relative;">
            <b>신고하기</b>&nbsp;&nbsp;
            <?php if ($isOnline) { ?>
                <a href="javascript:showInfo('report');">jnc@jncgift.co.kr</a>
            <?php } else { ?>
                <a href="mailto:jnc@jncgift.co.kr?subject=SuperWinGame 신고 접수&body=고객번호%20%3A<?=$authUserNo?>%0D%0A닉네임%20%3A<?=$authUserName?>%0D%0A게임명%20%3A%0D%0A발생시간%20%3A%0D%0A내용%20%3A%0D%0A%0D%0A%0D%0A상세내용과 함께 스크린샷을 첨부해주시면 정확하고 빠른 답변이 가능합니다.">jnc@jncgift.co.kr</a>
            <?php } ?>
            <span class="desc">신고한 내용은 회신을 받지 않을 수 있습니다.</span>
        </div>
        <?php
        if ($authUserID !== "") {
        ?>
		<div class="basic_padding">
			<b>회원정보<?=$authUserID?></b>
		</div>
        <?php
        }
        ?>
        <div class="footer">
            <b>
                상호: (주)플릭스쓰리엔터테인먼트<br/>
                대표이사: 황재욱<br/>
                <span>본사: 서울특별시 성동구 상원1길 25 4320호(성수동1가)</span>
                통신판매업 신고번호: 제 2021-서울성동-00078호<br/>
                게임물 등급분류번호: 제 호
            </b>
        </div>
    </section>
</div>
<style>
    /* 안내 */
    #infoModal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; }
    #modal-layer { position: absolute; left: 0; top: 0; text-align: center; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.8); }
    .modal-content { background-color: #fefefe; margin: 100px auto;  border: 1px solid #c1c1c1; width: 460px; }
    .pop_mid {}
    .pop_info { padding: 5px 0; background-color: #545454; color: #fff; line-height: 22px;}
    .pop_info p { text-align: center; font-size: 16px; color: #f3f1f1; letter-spacing: -0.8px;}
    .pop_info p span { display: inline-block; padding: 11px 17px; margin: 0 10px; background-color: #ff6a00; border-radius: 20px; font-size: 14px; color: #fff;}
    .pop_info p em { font-size: 12px; color: #999; margin-left: 5px;}
    .pop_subject { margin: 0 10px; width: 440px; padding: 10px 0px 20px; border-bottom: 1px solid #e2e2e2;}
    .pop_subject p{ text-align: left; line-height: 23px;  color: #6d6458; }
    .pop_subject p span { color: #555; font-weight: bold;}
    #report_add { display: none; }
    .pop_btm { }
    .pop_btm .pop_btn { clear: both;  margin: 22px auto; width: 80px; height: 40px;  }
    .pop_btm .pop_btn a { display: block; float: left; margin: 0 auto; width: 160px;  height: 40px; line-height: 39px; font-size: 16px; text-align: center; background-color: #ff6a00; border-radius: 5px; color: #fff;}
    .pop_btm .pop_btn a.close { width: 80px; margin: 0 10px; background-color: #807d7d; }

    .pop_btm .pop_btn a.ok:hover { background-color: #ff7716;}
</style>
<!-- 모달 -->
<div id="infoModal" class="modal">
    <div id="modal-layer">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="pop_mid">
                <div class="pop_info">
                    <p><label id="pop_title">문의하기</label> 안내</p>
                </div>
                <div class="pop_subject">
                    <p class="pop_box">
                        <span id="mail_type">jnc@jncgift.co.kr</span>메일로 아래내용과 함께 보내주세요.
                    </p>
                    <p>
                        제목 : <span id="mail_subject">SuperWinGame 게임관련 문의</span><br>
                        고객번호 : <span id="mail_userSeq"><?=$authUserNo?></span><br>
                        닉네임 : <span id="mail_nickname"><?=$authUserName?></span><br>
                        <label id="report_add">
                            게임명 : <br>
                            발생시간 : <br>
                        </label>
                        내용 : <span id="mail_content"></span><br>
                        상세내용과 함께 스크린샷을 첨부해주시면 정확하고 빠른 답변이 가능합니다.
                    </p>
                </div>
                <div class="pop_btm">
                    <div class="pop_btn">
                        <a href="javascript:closeModal();" class="close">닫기</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const jsoncache = Math.floor(+ new Date() / 60000);
    const notice_json = "./file/notice.json?v="+jsoncache;
    const faq_json = "./file/faq.json?v="+jsoncache;
    const limit_count = 5;

    $(document).ready(function() {
        let draw = function(target, json) {
            let articleCount = 0;
            $('#nav_'+target).empty();
            $(json).each(function(index, data) {
                if (articleCount >= limit_count) {
                    return false;
                }
                let border = (index === 0)? 2 : 1;
                let li = '<li style="border-top: '+ border +'px solid #f1f1f1;">' +
                    '<div class="basic_padding cs_title">' + data.title + ' ' +
                    '<span>'+ data.date.substr(0, 10).replace(/-/gi, '.') +'</span></div>' +
                    '<ul>' +
                    '<li>' +
                    '<div class="cs_contents">' + data.contents + '</div>' +
                    '</li>' +
                    '</ul>' +
                    '</li>';

                $('#nav_'+target).append(li);
                articleCount++;
            });
        };

        // load json
        $.getJSON(notice_json, function(json) {
            console.log(json);
            draw('notice', json);
        });

        $.getJSON(faq_json, function(json) {
            console.log(json);
            draw('faq', json);
        });

    });

    $(document).on('click', '.cs_list > li', function () {
        // console.log($(this).find('ul').is(':hidden'));
        if($(this).find('ul').is(':hidden')){
            $('.cs_list ul').slideUp();
            $(this).find('ul').slideDown();
        } else {
            $(this).find('ul').slideUp();
        }
    });
    //
    // $(document).on('click', '#nav_faq > li', function () {
    //     // console.log($(this).find('ul').is(':hidden'));
    //     if($(this).find('ul').is(':hidden')){
    //         $('#nav_faq ul').slideUp();
    //         $(this).find('ul').slideDown();
    //     } else {
    //         $(this).find('ul').slideUp();
    //     }
    // });

    $("#btn_1").click(function(){
        location.href="/publish/notice/NoticeList.html";
    });

    $("#btn_2").click(function(){
        location.href="/publish/faq/FaqList.html";
    });

    function showInfo(type) {
        let popTitle = (type === 'report')? '신고하기' : '문의하기';
        let toEmail = 'jnc@jncgift.co.kr';

        $('#pop_title').text(popTitle);
        $('#mail_type').text(toEmail);
        if (type === 'report') {
            $('#report_add').show();
        } else {
            $('#report_add').hide();
        }

        $('#infoModal').show();
    }

    function closeModal()
    {
        $('#infoModal').hide();
    }
</script>
</body>
</html>
