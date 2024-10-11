<?php
$authUserNo = 0;		// 고객번호
$authUserName = "";		// 닉네임
$authUserID = "";		// 회원정보

$authUserNo = isset($_GET['authUserNo'])? $_GET['authUserNo'] : (isset($_POST['authUserNo'])? $_POST['authUserNo'] : "");
$authUserName = isset($_GET['authUserName'])? $_GET['authUserName'] : (isset($_POST['authUserName'])? $_POST['authUserName'] : "");
$authUserID = isset($_GET['authUserID'])? $_GET['authUserID'] : (isset($_POST['authUserID'])? $_POST['authUserID'] : "");
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

    <link type="text/css" href="../assets/css/style.css?v=20200818" rel="stylesheet" />
    <script src="../assets/js/jquery.2.2.4.min.js"></script>
    <script src="../assets/js/common.js?v=20200818"></script>
 </head>
<body>
<!-- wrap -->
<div id="page">
    <section id="att_bg">
        <div id="btn_1" class="basic_padding cs_name"><b>공지사항</b><div class="cs_arrow"></div></div>
        <ul id="nav_notice">
        </ul>
        <div id="btn_2" class="basic_padding cs_name"><b>FAQ</b><div class="cs_arrow"></div></div>
        <ul id="nav_faq">
        </ul>
        <div class="basic_padding">
            <b>문의하기</b>&nbsp;&nbsp;
            <a href="mailto:jnc@jncgift.co.kr?subject=SuperWinGame 게임관련 문의&body=고객번호:<?=$authUserNo?>%0D%0A닉네임:<?=$authUserName?>%0D%0A내용:">jnc@jncgift.co.kr</a>
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
<script>
    const jsoncache = Math.floor(+ new Date() / 60000);
    const notice_json = "../file/slot/notice.json?v="+jsoncache;
    const faq_json = "../file/slot/faq.json?v="+jsoncache;
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
                    '<div class="basic_padding cs_contents">' + data.contents + '</div>' +
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

    $(document).on('click', '#nav_notice > li', function () {
        console.log($(this).find('ul').is(':hidden'));
        if($(this).find('ul').is(':hidden')){
            $('#nav ul').slideUp();
            $(this).find('ul').slideDown();
        } else {
            $(this).find('ul').slideUp();
        }
    });

    $(document).on('click', '#nav_faq > li', function () {
        console.log($(this).find('ul').is(':hidden'));
        if($(this).find('ul').is(':hidden')){
            $('#nav ul').slideUp();
            $(this).find('ul').slideDown();
        } else {
            $(this).find('ul').slideUp();
        }
    });

    $("#btn_1").click(function(){
        location.href="/publish/slot/notice/NoticeList.html";
    });

    $("#btn_2").click(function(){
        location.href="/publish/slot/faq/FaqList.html";
    });
</script>
</body>
</html>
