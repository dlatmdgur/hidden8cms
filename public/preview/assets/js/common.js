$(function(){
    var $section  = $("#culture_sct");
    var $listbox  = $section.find(".att_list").eq(0);
    var $article  = $listbox.find("article");
    for(var i=0; i<3; i++) {
        $listbox.append($article.clone());
    }
});

$(document).ready(function () {
    $(".scroll").click(function(event){
        event.preventDefault();
        $('html,body').animate({scrollTop:$(this.hash).offset().top}, 300);
    });
});

$(document).ready(function() {
    //$(".att_sublist").show();
    //att_sublist 클래스를 가진 div를 표시/숨김(토글)
    $(".att_title").click(function()
    {
        $(this).next(".att_sublist").slideToggle();
    });

    $("#viewhidden1_1").click(function () {
        $("#hidden1_1").css("display", "");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");

        $("#hidden15_1").css("display", "none");
        $("#viewhidden1_1").css("color", "#FFBB00");
        $("#viewhidden1_2").css("color", "");
        $("#viewhidden1_3").css("color", "");
        $("#hidden16_1").css("display", "none");

    });

    $("#viewhidden1_2").click(function () {
        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");
        $("#hidden15_1").css("display", "none");
        $("#viewhidden1_1").css("color", "");
        $("#viewhidden1_2").css("color", "#FFBB00");
        $("#viewhidden1_3").css("color", "");
        $("#hidden16_1").css("display", "none");
    });

    $("#viewhidden1_3").click(function () {
        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");

        $("#hidden15_1").css("display", "none");
        $("#viewhidden1_1").css("color", "");
        $("#viewhidden1_2").css("color", "");
        $("#viewhidden1_3").css("color", "#FFBB00");
        $("#hidden16_1").css("display", "none");
    });


    $("#viewhidden17_1").click(function () {
        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden17_1").css("display", "");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");
        $("#hidden15_1").css("display", "none");
        $("#viewhidden17_1").css("color", "#FFBB00");
        $("#viewhidden17_2").css("color", "");
        $("#viewhidden17_3").css("color", "");
        $("#hidden16_1").css("display", "none");

    });

    $("#viewhidden17_2").click(function () {
        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "");
        $("#hidden17_3").css("display", "none");

        $("#hidden15_1").css("display", "none");
        $("#viewhidden17_1").css("color", "");
        $("#viewhidden17_2").css("color", "#FFBB00");
        $("#viewhidden17_3").css("color", "");
        $("#hidden16_1").css("display", "none");
    });

    $("#viewhidden17_3").click(function () {
        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "");

        $("#hidden15_1").css("display", "none");
        $("#viewhidden17_1").css("color", "");
        $("#viewhidden17_2").css("color", "");
        $("#viewhidden17_3").css("color", "#FFBB00");
        $("#hidden16_1").css("display", "none");
    });


    $("#viewhidden2_1").click(function () {
        $("#hidden2_1").css("display", "");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");
        $("#hidden15_1").css("display", "none");
        $("#viewhidden2_1").css("color", "#FFBB00");
        $("#viewhidden2_2").css("color", "");
        $("#viewhidden2_3").css("color", "");
        $("#hidden16_1").css("display", "none");

    });

    $("#viewhidden2_2").click(function () {
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "");
        $("#hidden2_3").css("display", "none");
        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");
        $("#hidden15_1").css("display", "none");
        $("#viewhidden2_1").css("color", "");
        $("#viewhidden2_2").css("color", "#FFBB00");
        $("#viewhidden2_3").css("color", "");
        $("#hidden16_1").css("display", "none");
    });

    $("#viewhidden2_3").click(function () {
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "");
        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");
        $("#hidden15_1").css("display", "none");
        $("#viewhidden2_1").css("color", "");
        $("#viewhidden2_2").css("color", "");
        $("#viewhidden2_3").css("color", "#FFBB00");
        $("#hidden16_1").css("display", "none");
    });

    $("#viewhidden3_1").click(function () {
        $("#hidden3_1").css("display", "");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");
        $("#hidden15_1").css("display", "none");
        $("#viewhidden3_1").css("color", "#FFBB00");
        $("#viewhidden3_2").css("color", "");
        $("#viewhidden3_3").css("color", "");
        $("#hidden16_1").css("display", "none");

    });

    $("#viewhidden3_2").click(function () {
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "");
        $("#hidden3_3").css("display", "none");
        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");

        $("#hidden15_1").css("display", "none");
        $("#viewhidden3_1").css("color", "");
        $("#viewhidden3_2").css("color", "#FFBB00");
        $("#viewhidden3_3").css("color", "");
        $("#hidden16_1").css("display", "none");
    });

    $("#viewhidden3_3").click(function () {
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "");
        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");

        $("#hidden15_1").css("display", "none");
        $("#viewhidden3_1").css("color", "");
        $("#viewhidden3_2").css("color", "");
        $("#viewhidden3_3").css("color", "#FFBB00");
        $("#hidden16_1").css("display", "none");
    });

    $("#viewhidden4_1").click(function () {
        $("#hidden4_1").css("display", "");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");
        $("#hidden15_1").css("display", "none");
        $("#viewhidden4_1").css("color", "#FFBB00");
        $("#viewhidden4_2").css("color", "");
        $("#viewhidden4_3").css("color", "");
        $("#hidden16_1").css("display", "none");

    });

    $("#viewhidden4_2").click(function () {
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "");
        $("#hidden4_3").css("display", "none");
        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");
        $("#hidden15_1").css("display", "none");
        $("#viewhidden4_1").css("color", "");
        $("#viewhidden4_2").css("color", "#FFBB00");
        $("#viewhidden4_3").css("color", "");
        $("#hidden16_1").css("display", "none");
    });

    $("#viewhidden4_3").click(function () {
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "");
        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");
        $("#hidden15_1").css("display", "none");
        $("#viewhidden4_1").css("color", "");
        $("#viewhidden4_2").css("color", "");
        $("#viewhidden4_3").css("color", "#FFBB00");
        $("#hidden16_1").css("display", "none");
    });

    //대메뉴
    $("#m_btn1").css("color", "#FFBB00");
    $("#m_btn2").css("color", "");
    $("#m_btn3").css("color", "");
    $("#m_btn4").css("color", "");
    $("#m_btn5").css("color", "");
    $("#m_btn6").css("color", "");
    $("#m_btn7").css("color", "");
    $("#m_btn8").css("color", "");
    $("#m_btn9").css("color", "");
    $("#m_btn10").css("color", "");
    $("#m_btn11").css("color", "");
    $("#m_btn17").css("color", "");

    $("#hidden1").css("display", "");
    $("#hidden2").css("display", "none");
    $("#hidden3").css("display", "none");
    $("#hidden4").css("display", "none");
    $("#hidden5").css("display", "none");
    $("#hidden6").css("display", "none");
    $("#hidden7").css("display", "none");
    $("#hidden8").css("display", "none");
    $("#hidden9").css("display", "none");
    $("#hidden10").css("display", "none");
    $("#hidden12").css("display", "none");
    $("#hidden13").css("display", "none");
    $("#hidden14").css("display", "none");
    $("#hidden15").css("display", "none");
    $("#hidden16").css("display", "none");
    $("#hidden17").css("display", "none");

    $("#viewhidden1_1").css("color", "#FFBB00");
    $("#viewhidden1_2").css("color", "");
    $("#viewhidden1_3").css("color", "");
    $("#viewhidden2_1").css("color", "");
    $("#viewhidden2_2").css("color", "");
    $("#viewhidden2_3").css("color", "");
    $("#viewhidden3_1").css("color", "");
    $("#viewhidden3_2").css("color", "");
    $("#viewhidden3_3").css("color", "");
    $("#viewhidden4_1").css("color", "");
    $("#viewhidden4_2").css("color", "");
    $("#viewhidden4_3").css("color", "");
    $("#viewhidden17_1").css("color", "");
    $("#viewhidden17_2").css("color", "");
    $("#viewhidden17_3").css("color", "");

    $("#hidden1_1").css("display", "");
    $("#hidden1_2").css("display", "none");
    $("#hidden1_3").css("display", "none");
    $("#hidden2_1").css("display", "none");
    $("#hidden2_2").css("display", "none");
    $("#hidden2_3").css("display", "none");
    $("#hidden3_1").css("display", "none");
    $("#hidden3_2").css("display", "none");
    $("#hidden3_3").css("display", "none");
    $("#hidden4_1").css("display", "none");
    $("#hidden4_2").css("display", "none");
    $("#hidden4_3").css("display", "none");
    $("#hidden5_1").css("display", "none");
    $("#hidden6_1").css("display", "none");
    $("#hidden7_1").css("display", "none");
    $("#hidden8_1").css("display", "none");
    $("#hidden9_1").css("display", "none");
    $("#hidden10_1").css("display", "none");
    $("#hidden12_1").css("display", "none");
    $("#hidden13_1").css("display", "none");
    $("#hidden14_1").css("display", "none");
    $("#hidden15_1").css("display", "none");

    $("#hidden17_1").css("display", "none");
    $("#hidden17_2").css("display", "none");
    $("#hidden17_3").css("display", "none");

    $("#hidden11").css("display", "");
    $("#hidden22").css("display", "none");
    $("#hidden33").css("display", "none");
    $("#hidden44").css("display", "none");
    $("#hidden171").css("display", "none");
    $("#hidden16_1").css("display", "none");

});

function m_check(num){
    var num;
    if (num=="1")
    {
        $("#hidden1").css("display", "");
        $("#hidden2").css("display", "none");
        $("#hidden3").css("display", "none");
        $("#hidden4").css("display", "none");
        $("#hidden5").css("display", "none");
        $("#hidden6").css("display", "none");
        $("#hidden7").css("display", "none");
        $("#hidden8").css("display", "none");
        $("#hidden9").css("display", "none");
        $("#hidden10").css("display", "none");
        $("#hidden12").css("display", "none");
        $("#hidden13").css("display", "none");
        $("#hidden14").css("display", "none");
        $("#hidden17").css("display", "none");


        $("#hidden11").css("display", "");
        $("#hidden22").css("display", "none");
        $("#hidden33").css("display", "none");
        $("#hidden44").css("display", "none");
        $("#hidden171").css("display", "none");

        $("#m_btn1").css("color", "#FFBB00");
        $("#m_btn2").css("color", "");
        $("#m_btn3").css("color", "");
        $("#m_btn4").css("color", "");
        $("#m_btn5").css("color", "");
        $("#m_btn6").css("color", "");
        $("#m_btn7").css("color", "");
        $("#m_btn8").css("color", "");
        $("#m_btn9").css("color", "");
        $("#m_btn10").css("color", "");
        $("#m_btn17").css("color", "");


        $("#hidden1_1").css("display", "");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden5_1").css("display", "none");
        $("#hidden6_1").css("display", "none");
        $("#hidden7_1").css("display", "none");
        $("#hidden8_1").css("display", "none");
        $("#hidden9_1").css("display", "none");
        $("#hidden10_1").css("display", "none");
        $("#hidden12_1").css("display", "none");
        $("#hidden13_1").css("display", "none");
        $("#hidden14_1").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");

        $("#viewhidden1_1").css("color", "#FFBB00");
        $("#viewhidden1_2").css("color", "");
        $("#viewhidden1_3").css("color", "");
        $("#viewhidden2_1").css("color", "");
        $("#viewhidden2_2").css("color", "");
        $("#viewhidden2_3").css("color", "");
        $("#viewhidden3_1").css("color", "");
        $("#viewhidden3_2").css("color", "");
        $("#viewhidden3_3").css("color", "");
        $("#viewhidden4_1").css("color", "");
        $("#viewhidden4_2").css("color", "");
        $("#viewhidden4_3").css("color", "");
        $("#viewhidden17_1").css("color", "");
        $("#viewhidden17_2").css("color", "");
        $("#viewhidden17_3").css("color", "");

        $("#hidden15").css("display", "none");
        $("#m_btn11").css("color", "");
        $("#hidden15_1").css("display", "none");

        $("#hidden16").css("display", "none");
        $("#hidden16_1").css("display", "none");

    }
    else if (num=="17")
    {
        $("#hidden1").css("display", "none");
        $("#hidden2").css("display", "none");
        $("#hidden3").css("display", "none");
        $("#hidden4").css("display", "none");
        $("#hidden5").css("display", "none");
        $("#hidden6").css("display", "none");
        $("#hidden7").css("display", "none");
        $("#hidden8").css("display", "none");
        $("#hidden9").css("display", "none");
        $("#hidden10").css("display", "none");
        $("#hidden12").css("display", "none");
        $("#hidden13").css("display", "none");
        $("#hidden14").css("display", "none");
        $("#hidden17").css("display", "");


        $("#hidden11").css("display", "none");
        $("#hidden22").css("display", "none");
        $("#hidden33").css("display", "none");
        $("#hidden44").css("display", "none");
        $("#hidden171").css("display", "");

        $("#m_btn1").css("color", "");
        $("#m_btn2").css("color", "");
        $("#m_btn3").css("color", "");
        $("#m_btn4").css("color", "");
        $("#m_btn5").css("color", "");
        $("#m_btn6").css("color", "");
        $("#m_btn7").css("color", "");
        $("#m_btn8").css("color", "");
        $("#m_btn9").css("color", "");
        $("#m_btn10").css("color", "");
        $("#m_btn17").css("color", "#FFBB00");

        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden5_1").css("display", "none");
        $("#hidden6_1").css("display", "none");
        $("#hidden7_1").css("display", "none");
        $("#hidden8_1").css("display", "none");
        $("#hidden9_1").css("display", "none");
        $("#hidden10_1").css("display", "none");
        $("#hidden12_1").css("display", "none");
        $("#hidden13_1").css("display", "none");
        $("#hidden14_1").css("display", "none");
        $("#hidden17_1").css("display", "");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");

        $("#viewhidden1_1").css("color", "");
        $("#viewhidden1_2").css("color", "");
        $("#viewhidden1_3").css("color", "");
        $("#viewhidden2_1").css("color", "");
        $("#viewhidden2_2").css("color", "");
        $("#viewhidden2_3").css("color", "");
        $("#viewhidden3_1").css("color", "");
        $("#viewhidden3_2").css("color", "");
        $("#viewhidden3_3").css("color", "");
        $("#viewhidden4_1").css("color", "");
        $("#viewhidden4_2").css("color", "");
        $("#viewhidden4_3").css("color", "");
        $("#viewhidden17_1").css("color", "#FFBB00");
        $("#viewhidden17_2").css("color", "");
        $("#viewhidden17_3").css("color", "");

        $("#hidden15").css("display", "none");
        $("#m_btn11").css("color", "");
        $("#hidden15_1").css("display", "none");

        $("#hidden16").css("display", "none");
        $("#hidden16_1").css("display", "none");

    }
    else if (num=="2")
    {
        $("#hidden1").css("display", "none");
        $("#hidden2").css("display", "");
        $("#hidden3").css("display", "none");
        $("#hidden4").css("display", "none");
        $("#hidden5").css("display", "none");
        $("#hidden6").css("display", "none");
        $("#hidden7").css("display", "none");
        $("#hidden8").css("display", "none");
        $("#hidden9").css("display", "none");
        $("#hidden10").css("display", "none");
        $("#hidden12").css("display", "none");
        $("#hidden13").css("display", "none");
        $("#hidden14").css("display", "none");

        $("#hidden11").css("display", "none");
        $("#hidden22").css("display", "");
        $("#hidden33").css("display", "none");
        $("#hidden44").css("display", "none");
        $("#hidden171").css("display", "none");


        $("#m_btn1").css("color", "");
        $("#m_btn2").css("color", "#FFBB00");
        $("#m_btn3").css("color", "");
        $("#m_btn4").css("color", "");
        $("#m_btn5").css("color", "");
        $("#m_btn6").css("color", "");
        $("#m_btn7").css("color", "");
        $("#m_btn8").css("color", "");
        $("#m_btn9").css("color", "");
        $("#m_btn10").css("color", "");
        $("#m_btn17").css("color", "");
        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden5_1").css("display", "none");
        $("#hidden6_1").css("display", "none");
        $("#hidden7_1").css("display", "none");
        $("#hidden8_1").css("display", "none");
        $("#hidden9_1").css("display", "none");
        $("#hidden10_1").css("display", "none");
        $("#hidden12_1").css("display", "none");
        $("#hidden13_1").css("display", "none");
        $("#hidden14_1").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");


        $("#viewhidden1_1").css("color", "");
        $("#viewhidden1_2").css("color", "");
        $("#viewhidden1_3").css("color", "");
        $("#viewhidden2_1").css("color", "#FFBB00");
        $("#viewhidden2_2").css("color", "");
        $("#viewhidden2_3").css("color", "");
        $("#viewhidden3_1").css("color", "");
        $("#viewhidden3_2").css("color", "");
        $("#viewhidden3_3").css("color", "");
        $("#viewhidden4_1").css("color", "");
        $("#viewhidden4_2").css("color", "");
        $("#viewhidden4_3").css("color", "");
        $("#viewhidden17_1").css("color", "");
        $("#viewhidden17_2").css("color", "");
        $("#viewhidden17_3").css("color", "");

        $("#hidden15").css("display", "none");
        $("#m_btn11").css("color", "");
        $("#hidden15_1").css("display", "none");

        $("#hidden16").css("display", "none");
        $("#hidden16_1").css("display", "none");
    }
    else if (num=="3")
    {
        $("#hidden1").css("display", "none");
        $("#hidden2").css("display", "none");
        $("#hidden3").css("display", "");
        $("#hidden4").css("display", "none");
        $("#hidden5").css("display", "none");
        $("#hidden6").css("display", "none");
        $("#hidden7").css("display", "none");
        $("#hidden8").css("display", "none");
        $("#hidden9").css("display", "none");
        $("#hidden10").css("display", "none");
        $("#hidden12").css("display", "none");
        $("#hidden13").css("display", "none");
        $("#hidden14").css("display", "none");

        $("#hidden11").css("display", "none");
        $("#hidden22").css("display", "none");
        $("#hidden33").css("display", "");
        $("#hidden44").css("display", "none");
        $("#hidden171").css("display", "none");

        $("#m_btn1").css("color", "");
        $("#m_btn2").css("color", "");
        $("#m_btn3").css("color", "#FFBB00");
        $("#m_btn4").css("color", "");
        $("#m_btn5").css("color", "");
        $("#m_btn6").css("color", "");
        $("#m_btn7").css("color", "");
        $("#m_btn8").css("color", "");
        $("#m_btn9").css("color", "");
        $("#m_btn10").css("color", "");
        $("#m_btn17").css("color", "");

        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden3_1").css("display", "");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden5_1").css("display", "none");
        $("#hidden6_1").css("display", "none");
        $("#hidden7_1").css("display", "none");
        $("#hidden8_1").css("display", "none");
        $("#hidden9_1").css("display", "none");
        $("#hidden10_1").css("display", "none");
        $("#hidden12_1").css("display", "none");
        $("#hidden13_1").css("display", "none");
        $("#hidden14_1").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");

        $("#viewhidden1_1").css("color", "");
        $("#viewhidden1_2").css("color", "");
        $("#viewhidden1_3").css("color", "");
        $("#viewhidden2_1").css("color", "");
        $("#viewhidden2_2").css("color", "");
        $("#viewhidden2_3").css("color", "");
        $("#viewhidden3_1").css("color", "#FFBB00");
        $("#viewhidden3_2").css("color", "");
        $("#viewhidden3_3").css("color", "");
        $("#viewhidden4_1").css("color", "");
        $("#viewhidden4_2").css("color", "");
        $("#viewhidden4_3").css("color", "");
        $("#viewhidden17_1").css("color", "");
        $("#viewhidden17_2").css("color", "");
        $("#viewhidden17_3").css("color", "");

        $("#hidden15").css("display", "none");
        $("#m_btn11").css("color", "");
        $("#hidden15_1").css("display", "none");

        $("#hidden16").css("display", "none");
        $("#hidden16_1").css("display", "none");

    }
    else if (num=="4")
    {
        $("#hidden1").css("display", "none");
        $("#hidden2").css("display", "none");
        $("#hidden3").css("display", "none");
        $("#hidden4").css("display", "");
        $("#hidden5").css("display", "none");
        $("#hidden6").css("display", "none");
        $("#hidden7").css("display", "none");
        $("#hidden8").css("display", "none");
        $("#hidden9").css("display", "none");
        $("#hidden10").css("display", "none");
        $("#hidden12").css("display", "none");
        $("#hidden13").css("display", "none");
        $("#hidden14").css("display", "none");

        $("#hidden11").css("display", "none");
        $("#hidden22").css("display", "none");
        $("#hidden33").css("display", "none");
        $("#hidden44").css("display", "");
        $("#hidden171").css("display", "none");


        $("#m_btn1").css("color", "");
        $("#m_btn2").css("color", "");
        $("#m_btn3").css("color", "");
        $("#m_btn4").css("color", "#FFBB00");
        $("#m_btn5").css("color", "");
        $("#m_btn6").css("color", "");
        $("#m_btn7").css("color", "");
        $("#m_btn8").css("color", "");
        $("#m_btn9").css("color", "");
        $("#m_btn10").css("color", "");
        $("#m_btn17").css("color", "");

        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden4_1").css("display", "");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden5_1").css("display", "none");
        $("#hidden6_1").css("display", "none");
        $("#hidden7_1").css("display", "none");
        $("#hidden8_1").css("display", "none");
        $("#hidden9_1").css("display", "none");
        $("#hidden10_1").css("display", "none");
        $("#hidden12_1").css("display", "none");
        $("#hidden13_1").css("display", "none");
        $("#hidden14_1").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");

        $("#viewhidden1_1").css("color", "");
        $("#viewhidden1_2").css("color", "");
        $("#viewhidden1_3").css("color", "");
        $("#viewhidden2_1").css("color", "");
        $("#viewhidden2_2").css("color", "");
        $("#viewhidden2_3").css("color", "");
        $("#viewhidden3_1").css("color", "");
        $("#viewhidden3_2").css("color", "");
        $("#viewhidden3_3").css("color", "");
        $("#viewhidden4_1").css("color", "#FFBB00");
        $("#viewhidden4_2").css("color", "");
        $("#viewhidden4_3").css("color", "");
        $("#viewhidden17_1").css("color", "");
        $("#viewhidden17_2").css("color", "");
        $("#viewhidden17_3").css("color", "");

        $("#hidden15").css("display", "none");
        $("#m_btn11").css("color", "");
        $("#hidden15_1").css("display", "none");

        $("#hidden16").css("display", "none");
        $("#hidden16_1").css("display", "none");
    }
    else if (num=="5")
    {
        $("#hidden1").css("display", "none");
        $("#hidden2").css("display", "none");
        $("#hidden3").css("display", "none");
        $("#hidden4").css("display", "none");
        $("#hidden5").css("display", "");
        $("#hidden6").css("display", "none");
        $("#hidden7").css("display", "none");
        $("#hidden8").css("display", "none");
        $("#hidden9").css("display", "none");
        $("#hidden10").css("display", "none");
        $("#hidden12").css("display", "none");
        $("#hidden13").css("display", "none");
        $("#hidden14").css("display", "none");

        $("#hidden11").css("display", "none");
        $("#hidden22").css("display", "none");
        $("#hidden33").css("display", "none");
        $("#hidden44").css("display", "none");
        $("#hidden171").css("display", "none");


        $("#m_btn1").css("color", "");
        $("#m_btn2").css("color", "");
        $("#m_btn3").css("color", "");
        $("#m_btn4").css("color", "");
        $("#m_btn5").css("color", "#FFBB00");
        $("#m_btn6").css("color", "");
        $("#m_btn7").css("color", "");
        $("#m_btn8").css("color", "");
        $("#m_btn9").css("color", "");
        $("#m_btn10").css("color", "");
        $("#m_btn17").css("color", "");

        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden5_1").css("display", "");
        $("#hidden6_1").css("display", "none");
        $("#hidden7_1").css("display", "none");
        $("#hidden8_1").css("display", "none");
        $("#hidden9_1").css("display", "none");
        $("#hidden10_1").css("display", "none");
        $("#hidden12_1").css("display", "none");
        $("#hidden13_1").css("display", "none");
        $("#hidden14_1").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");

        $("#viewhidden1_1").css("color", "");
        $("#viewhidden1_2").css("color", "");
        $("#viewhidden1_3").css("color", "");
        $("#viewhidden2_1").css("color", "");
        $("#viewhidden2_2").css("color", "");
        $("#viewhidden2_3").css("color", "");
        $("#viewhidden3_1").css("color", "");
        $("#viewhidden3_2").css("color", "");
        $("#viewhidden3_3").css("color", "");
        $("#viewhidden4_1").css("color", "");
        $("#viewhidden4_2").css("color", "");
        $("#viewhidden4_3").css("color", "");
        $("#viewhidden17_1").css("color", "");
        $("#viewhidden17_2").css("color", "");
        $("#viewhidden17_3").css("color", "");

        $("#hidden15").css("display", "none");
        $("#m_btn11").css("color", "");
        $("#hidden15_1").css("display", "none");

        $("#hidden16").css("display", "none");
        $("#hidden16_1").css("display", "none");
    }

    else if (num=="6")
    {
        $("#hidden1").css("display", "none");
        $("#hidden2").css("display", "none");
        $("#hidden3").css("display", "none");
        $("#hidden4").css("display", "none");
        $("#hidden5").css("display", "none");
        $("#hidden6").css("display", "");
        $("#hidden7").css("display", "none");
        $("#hidden8").css("display", "none");
        $("#hidden9").css("display", "none");
        $("#hidden10").css("display", "none");
        $("#hidden12").css("display", "none");
        $("#hidden13").css("display", "none");
        $("#hidden14").css("display", "none");

        $("#hidden11").css("display", "none");
        $("#hidden22").css("display", "none");
        $("#hidden33").css("display", "none");
        $("#hidden44").css("display", "none");
        $("#hidden171").css("display", "none");


        $("#m_btn1").css("color", "");
        $("#m_btn2").css("color", "");
        $("#m_btn3").css("color", "");
        $("#m_btn4").css("color", "");
        $("#m_btn5").css("color", "");
        $("#m_btn6").css("color", "#FFBB00");
        $("#m_btn7").css("color", "");
        $("#m_btn8").css("color", "");
        $("#m_btn9").css("color", "");
        $("#m_btn10").css("color", "");
        $("#m_btn17").css("color", "");

        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden5_1").css("display", "none");
        $("#hidden6_1").css("display", "");
        $("#hidden7_1").css("display", "none");
        $("#hidden8_1").css("display", "none");
        $("#hidden9_1").css("display", "none");
        $("#hidden10_1").css("display", "none");
        $("#hidden12_1").css("display", "none");
        $("#hidden13_1").css("display", "none");
        $("#hidden14_1").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");

        $("#viewhidden1_1").css("color", "");
        $("#viewhidden1_2").css("color", "");
        $("#viewhidden1_3").css("color", "");
        $("#viewhidden2_1").css("color", "");
        $("#viewhidden2_2").css("color", "");
        $("#viewhidden2_3").css("color", "");
        $("#viewhidden3_1").css("color", "");
        $("#viewhidden3_2").css("color", "");
        $("#viewhidden3_3").css("color", "");
        $("#viewhidden4_1").css("color", "");
        $("#viewhidden4_2").css("color", "");
        $("#viewhidden4_3").css("color", "");
        $("#viewhidden17_1").css("color", "");
        $("#viewhidden17_2").css("color", "");
        $("#viewhidden17_3").css("color", "");

        $("#hidden15").css("display", "none");
        $("#m_btn11").css("color", "");
        $("#hidden15_1").css("display", "none");

        $("#hidden16").css("display", "none");
        $("#hidden16_1").css("display", "none");
    }

    else if (num=="7")
    {
        $("#hidden1").css("display", "none");
        $("#hidden2").css("display", "none");
        $("#hidden3").css("display", "none");
        $("#hidden4").css("display", "none");
        $("#hidden5").css("display", "none");
        $("#hidden6").css("display", "none");
        $("#hidden7").css("display", "");
        $("#hidden8").css("display", "none");
        $("#hidden9").css("display", "none");
        $("#hidden10").css("display", "none");
        $("#hidden12").css("display", "none");
        $("#hidden13").css("display", "none");
        $("#hidden14").css("display", "none");

        $("#hidden11").css("display", "none");
        $("#hidden22").css("display", "none");
        $("#hidden33").css("display", "none");
        $("#hidden44").css("display", "none");
        $("#hidden171").css("display", "none");


        $("#m_btn1").css("color", "");
        $("#m_btn2").css("color", "");
        $("#m_btn3").css("color", "");
        $("#m_btn4").css("color", "");
        $("#m_btn5").css("color", "");
        $("#m_btn6").css("color", "");
        $("#m_btn7").css("color", "#FFBB00");
        $("#m_btn8").css("color", "");
        $("#m_btn9").css("color", "");
        $("#m_btn10").css("color", "");
        $("#m_btn17").css("color", "");

        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden5_1").css("display", "none");
        $("#hidden6_1").css("display", "none");
        $("#hidden7_1").css("display", "");
        $("#hidden8_1").css("display", "none");
        $("#hidden9_1").css("display", "none");
        $("#hidden10_1").css("display", "none");
        $("#hidden12_1").css("display", "none");
        $("#hidden13_1").css("display", "none");
        $("#hidden14_1").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");

        $("#viewhidden1_1").css("color", "");
        $("#viewhidden1_2").css("color", "");
        $("#viewhidden1_3").css("color", "");
        $("#viewhidden2_1").css("color", "");
        $("#viewhidden2_2").css("color", "");
        $("#viewhidden2_3").css("color", "");
        $("#viewhidden3_1").css("color", "");
        $("#viewhidden3_2").css("color", "");
        $("#viewhidden3_3").css("color", "");
        $("#viewhidden4_1").css("color", "");
        $("#viewhidden4_2").css("color", "");
        $("#viewhidden4_3").css("color", "");
        $("#viewhidden17_1").css("color", "");
        $("#viewhidden17_2").css("color", "");
        $("#viewhidden17_3").css("color", "");

        $("#hidden15").css("display", "none");
        $("#m_btn11").css("color", "");
        $("#hidden15_1").css("display", "none");

        $("#hidden16").css("display", "none");
        $("#hidden16_1").css("display", "none");
    }

    else if (num=="8")
    {
        $("#hidden1").css("display", "none");
        $("#hidden2").css("display", "none");
        $("#hidden3").css("display", "none");
        $("#hidden4").css("display", "none");
        $("#hidden5").css("display", "none");
        $("#hidden6").css("display", "none");
        $("#hidden7").css("display", "none");
        $("#hidden8").css("display", "");
        $("#hidden9").css("display", "none");
        $("#hidden10").css("display", "none");
        $("#hidden12").css("display", "none");
        $("#hidden13").css("display", "none");
        $("#hidden14").css("display", "none");

        $("#hidden11").css("display", "none");
        $("#hidden22").css("display", "none");
        $("#hidden33").css("display", "none");
        $("#hidden44").css("display", "none");
        $("#hidden171").css("display", "none");


        $("#m_btn1").css("color", "");
        $("#m_btn2").css("color", "");
        $("#m_btn3").css("color", "");
        $("#m_btn4").css("color", "");
        $("#m_btn5").css("color", "");
        $("#m_btn6").css("color", "");
        $("#m_btn7").css("color", "");
        $("#m_btn8").css("color", "#FFBB00");
        $("#m_btn9").css("color", "");
        $("#m_btn10").css("color", "");
        $("#m_btn17").css("color", "");

        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden5_1").css("display", "none");
        $("#hidden6_1").css("display", "none");
        $("#hidden7_1").css("display", "none");
        $("#hidden8_1").css("display", "");
        $("#hidden9_1").css("display", "none");
        $("#hidden10_1").css("display", "none");
        $("#hidden12_1").css("display", "none");
        $("#hidden13_1").css("display", "none");
        $("#hidden14_1").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");

        $("#viewhidden1_1").css("color", "");
        $("#viewhidden1_2").css("color", "");
        $("#viewhidden1_3").css("color", "");
        $("#viewhidden2_1").css("color", "");
        $("#viewhidden2_2").css("color", "");
        $("#viewhidden2_3").css("color", "");
        $("#viewhidden3_1").css("color", "");
        $("#viewhidden3_2").css("color", "");
        $("#viewhidden3_3").css("color", "");
        $("#viewhidden4_1").css("color", "");
        $("#viewhidden4_2").css("color", "");
        $("#viewhidden4_3").css("color", "");
        $("#viewhidden17_1").css("color", "");
        $("#viewhidden17_2").css("color", "");
        $("#viewhidden17_3").css("color", "");

        $("#hidden15").css("display", "none");
        $("#m_btn11").css("color", "");
        $("#hidden15_1").css("display", "none");

        $("#hidden16").css("display", "none");
        $("#hidden16_1").css("display", "none");
    }

    else if (num=="9")
    {
        $("#hidden1").css("display", "none");
        $("#hidden2").css("display", "none");
        $("#hidden3").css("display", "none");
        $("#hidden4").css("display", "none");
        $("#hidden5").css("display", "none");
        $("#hidden6").css("display", "none");
        $("#hidden7").css("display", "none");
        $("#hidden8").css("display", "none");
        $("#hidden9").css("display", "");
        $("#hidden10").css("display", "none");
        $("#hidden12").css("display", "none");
        $("#hidden13").css("display", "none");
        $("#hidden14").css("display", "none");

        $("#hidden11").css("display", "none");
        $("#hidden22").css("display", "none");
        $("#hidden33").css("display", "none");
        $("#hidden44").css("display", "none");
        $("#hidden171").css("display", "none");


        $("#m_btn1").css("color", "");
        $("#m_btn2").css("color", "");
        $("#m_btn3").css("color", "");
        $("#m_btn4").css("color", "");
        $("#m_btn5").css("color", "");
        $("#m_btn6").css("color", "");
        $("#m_btn7").css("color", "");
        $("#m_btn8").css("color", "");
        $("#m_btn9").css("color", "#FFBB00");
        $("#m_btn10").css("color", "");
        $("#m_btn17").css("color", "");

        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden5_1").css("display", "none");
        $("#hidden6_1").css("display", "none");
        $("#hidden7_1").css("display", "none");
        $("#hidden8_1").css("display", "none");
        $("#hidden9_1").css("display", "");
        $("#hidden10_1").css("display", "none");
        $("#hidden12_1").css("display", "none");
        $("#hidden13_1").css("display", "none");
        $("#hidden14_1").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");

        $("#viewhidden1_1").css("color", "");
        $("#viewhidden1_2").css("color", "");
        $("#viewhidden1_3").css("color", "");
        $("#viewhidden2_1").css("color", "");
        $("#viewhidden2_2").css("color", "");
        $("#viewhidden2_3").css("color", "");
        $("#viewhidden3_1").css("color", "");
        $("#viewhidden3_2").css("color", "");
        $("#viewhidden3_3").css("color", "");
        $("#viewhidden4_1").css("color", "");
        $("#viewhidden4_2").css("color", "");
        $("#viewhidden4_3").css("color", "");
        $("#viewhidden17_1").css("color", "");
        $("#viewhidden17_2").css("color", "");
        $("#viewhidden17_3").css("color", "");

        $("#hidden15").css("display", "none");
        $("#m_btn11").css("color", "");
        $("#hidden15_1").css("display", "none");

        $("#hidden16").css("display", "none");
        $("#hidden16_1").css("display", "none");
    }

    else if (num=="10")
    {
        $("#hidden1").css("display", "none");
        $("#hidden2").css("display", "none");
        $("#hidden3").css("display", "none");
        $("#hidden4").css("display", "none");
        $("#hidden5").css("display", "none");
        $("#hidden6").css("display", "none");
        $("#hidden7").css("display", "none");
        $("#hidden8").css("display", "none");
        $("#hidden9").css("display", "none");
        $("#hidden10").css("display", "");
        $("#hidden12").css("display", "none");
        $("#hidden13").css("display", "none");
        $("#hidden14").css("display", "none");

        $("#hidden11").css("display", "none");
        $("#hidden22").css("display", "none");
        $("#hidden33").css("display", "none");
        $("#hidden44").css("display", "none");
        $("#hidden171").css("display", "none");


        $("#m_btn1").css("color", "");
        $("#m_btn2").css("color", "");
        $("#m_btn3").css("color", "");
        $("#m_btn4").css("color", "");
        $("#m_btn5").css("color", "");
        $("#m_btn6").css("color", "");
        $("#m_btn7").css("color", "");
        $("#m_btn8").css("color", "");
        $("#m_btn9").css("color", "");
        $("#m_btn10").css("color", "#FFBB00");
        $("#m_btn17").css("color", "");

        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden5_1").css("display", "none");
        $("#hidden6_1").css("display", "none");
        $("#hidden7_1").css("display", "none");
        $("#hidden8_1").css("display", "none");
        $("#hidden9_1").css("display", "none");
        $("#hidden10_1").css("display", "");
        $("#hidden12_1").css("display", "none");
        $("#hidden13_1").css("display", "none");
        $("#hidden14_1").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");

        $("#viewhidden1_1").css("color", "");
        $("#viewhidden1_2").css("color", "");
        $("#viewhidden1_3").css("color", "");
        $("#viewhidden2_1").css("color", "");
        $("#viewhidden2_2").css("color", "");
        $("#viewhidden2_3").css("color", "");
        $("#viewhidden3_1").css("color", "");
        $("#viewhidden3_2").css("color", "");
        $("#viewhidden3_3").css("color", "");
        $("#viewhidden4_1").css("color", "");
        $("#viewhidden4_2").css("color", "");
        $("#viewhidden4_3").css("color", "");
        $("#viewhidden17_1").css("color", "");
        $("#viewhidden17_2").css("color", "");
        $("#viewhidden17_3").css("color", "");

        $("#hidden15").css("display", "none");
        $("#m_btn11").css("color", "");
        $("#hidden15_1").css("display", "none");

        $("#hidden16").css("display", "none");
        $("#hidden16_1").css("display", "none");
    }

    else if (num=="12")
    {
        $("#hidden1").css("display", "none");
        $("#hidden2").css("display", "none");
        $("#hidden3").css("display", "none");
        $("#hidden4").css("display", "none");
        $("#hidden5").css("display", "none");
        $("#hidden6").css("display", "none");
        $("#hidden7").css("display", "none");
        $("#hidden8").css("display", "none");
        $("#hidden9").css("display", "none");
        $("#hidden10").css("display", "none");
        $("#hidden12").css("display", "");
        $("#hidden13").css("display", "none");
        $("#hidden14").css("display", "none");

        $("#hidden11").css("display", "none");
        $("#hidden22").css("display", "none");
        $("#hidden33").css("display", "none");
        $("#hidden44").css("display", "none");
        $("#hidden171").css("display", "none");


        $("#m_btn1").css("color", "");
        $("#m_btn2").css("color", "");
        $("#m_btn3").css("color", "");
        $("#m_btn4").css("color", "");
        $("#m_btn5").css("color", "");
        $("#m_btn6").css("color", "");
        $("#m_btn7").css("color", "");
        $("#m_btn8").css("color", "");
        $("#m_btn9").css("color", "");
        $("#m_btn10").css("color", "");
        $("#m_btn17").css("color", "");

        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden5_1").css("display", "none");
        $("#hidden6_1").css("display", "none");
        $("#hidden7_1").css("display", "none");
        $("#hidden8_1").css("display", "none");
        $("#hidden9_1").css("display", "none");
        $("#hidden10_1").css("display", "none");
        $("#hidden12_1").css("display", "");
        $("#hidden13_1").css("display", "none");
        $("#hidden14_1").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");

        $("#viewhidden1_1").css("color", "");
        $("#viewhidden1_2").css("color", "");
        $("#viewhidden1_3").css("color", "");
        $("#viewhidden2_1").css("color", "");
        $("#viewhidden2_2").css("color", "");
        $("#viewhidden2_3").css("color", "");
        $("#viewhidden3_1").css("color", "");
        $("#viewhidden3_2").css("color", "");
        $("#viewhidden3_3").css("color", "");
        $("#viewhidden4_1").css("color", "");
        $("#viewhidden4_2").css("color", "");
        $("#viewhidden4_3").css("color", "");
        $("#viewhidden17_1").css("color", "");
        $("#viewhidden17_2").css("color", "");
        $("#viewhidden17_3").css("color", "");

        $("#hidden15").css("display", "none");
        $("#m_btn11").css("color", "");
        $("#hidden15_1").css("display", "none");

        $("#hidden16").css("display", "none");
        $("#hidden16_1").css("display", "none");
    }

    else if (num=="13")
    {
        $("#hidden1").css("display", "none");
        $("#hidden2").css("display", "none");
        $("#hidden3").css("display", "none");
        $("#hidden4").css("display", "none");
        $("#hidden5").css("display", "none");
        $("#hidden6").css("display", "none");
        $("#hidden7").css("display", "none");
        $("#hidden8").css("display", "none");
        $("#hidden9").css("display", "none");
        $("#hidden10").css("display", "none");
        $("#hidden12").css("display", "none");
        $("#hidden13").css("display", "");
        $("#hidden14").css("display", "none");

        $("#hidden11").css("display", "none");
        $("#hidden22").css("display", "none");
        $("#hidden33").css("display", "none");
        $("#hidden44").css("display", "none");
        $("#hidden171").css("display", "none");


        $("#m_btn1").css("color", "");
        $("#m_btn2").css("color", "");
        $("#m_btn3").css("color", "");
        $("#m_btn4").css("color", "");
        $("#m_btn5").css("color", "");
        $("#m_btn6").css("color", "");
        $("#m_btn7").css("color", "");
        $("#m_btn8").css("color", "");
        $("#m_btn9").css("color", "");
        $("#m_btn10").css("color", "");
        $("#m_btn17").css("color", "");

        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden5_1").css("display", "none");
        $("#hidden6_1").css("display", "none");
        $("#hidden7_1").css("display", "none");
        $("#hidden8_1").css("display", "none");
        $("#hidden9_1").css("display", "none");
        $("#hidden10_1").css("display", "none");
        $("#hidden12_1").css("display", "none");
        $("#hidden13_1").css("display", "");
        $("#hidden14_1").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");

        $("#viewhidden1_1").css("color", "");
        $("#viewhidden1_2").css("color", "");
        $("#viewhidden1_3").css("color", "");
        $("#viewhidden2_1").css("color", "");
        $("#viewhidden2_2").css("color", "");
        $("#viewhidden2_3").css("color", "");
        $("#viewhidden3_1").css("color", "");
        $("#viewhidden3_2").css("color", "");
        $("#viewhidden3_3").css("color", "");
        $("#viewhidden4_1").css("color", "");
        $("#viewhidden4_2").css("color", "");
        $("#viewhidden4_3").css("color", "");
        $("#viewhidden17_1").css("color", "");
        $("#viewhidden17_2").css("color", "");
        $("#viewhidden17_3").css("color", "");

        $("#hidden15").css("display", "none");
        $("#m_btn11").css("color", "");
        $("#hidden15_1").css("display", "none");

        $("#hidden16").css("display", "none");
        $("#hidden16_1").css("display", "none");
    }

    else if (num=="14")
    {
        $("#hidden1").css("display", "none");
        $("#hidden2").css("display", "none");
        $("#hidden3").css("display", "none");
        $("#hidden4").css("display", "none");
        $("#hidden5").css("display", "none");
        $("#hidden6").css("display", "none");
        $("#hidden7").css("display", "none");
        $("#hidden8").css("display", "none");
        $("#hidden9").css("display", "none");
        $("#hidden10").css("display", "none");
        $("#hidden12").css("display", "none");
        $("#hidden13").css("display", "none");
        $("#hidden14").css("display", "");

        $("#hidden11").css("display", "none");
        $("#hidden22").css("display", "none");
        $("#hidden33").css("display", "none");
        $("#hidden44").css("display", "none");
        $("#hidden171").css("display", "none");


        $("#m_btn1").css("color", "");
        $("#m_btn2").css("color", "");
        $("#m_btn3").css("color", "");
        $("#m_btn4").css("color", "");
        $("#m_btn5").css("color", "");
        $("#m_btn6").css("color", "");
        $("#m_btn7").css("color", "");
        $("#m_btn8").css("color", "");
        $("#m_btn9").css("color", "");
        $("#m_btn10").css("color", "");
        $("#m_btn17").css("color", "");

        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden5_1").css("display", "none");
        $("#hidden6_1").css("display", "none");
        $("#hidden7_1").css("display", "none");
        $("#hidden8_1").css("display", "none");
        $("#hidden9_1").css("display", "none");
        $("#hidden10_1").css("display", "none");
        $("#hidden12_1").css("display", "none");
        $("#hidden13_1").css("display", "none");
        $("#hidden14_1").css("display", "");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");

        $("#viewhidden1_1").css("color", "");
        $("#viewhidden1_2").css("color", "");
        $("#viewhidden1_3").css("color", "");
        $("#viewhidden2_1").css("color", "");
        $("#viewhidden2_2").css("color", "");
        $("#viewhidden2_3").css("color", "");
        $("#viewhidden3_1").css("color", "");
        $("#viewhidden3_2").css("color", "");
        $("#viewhidden3_3").css("color", "");
        $("#viewhidden4_1").css("color", "");
        $("#viewhidden4_2").css("color", "");
        $("#viewhidden4_3").css("color", "");
        $("#viewhidden17_1").css("color", "");
        $("#viewhidden17_2").css("color", "");
        $("#viewhidden17_3").css("color", "");

        $("#hidden15").css("display", "none");
        $("#m_btn11").css("color", "");
        $("#hidden15_1").css("display", "none");

        $("#hidden16").css("display", "none");
        $("#hidden16_1").css("display", "none");
    }

    else if (num=="15")
    {
        $("#hidden1").css("display", "none");
        $("#hidden2").css("display", "none");
        $("#hidden3").css("display", "none");
        $("#hidden4").css("display", "none");
        $("#hidden5").css("display", "none");
        $("#hidden6").css("display", "none");
        $("#hidden7").css("display", "none");
        $("#hidden8").css("display", "none");
        $("#hidden9").css("display", "none");
        $("#hidden10").css("display", "none");
        $("#hidden12").css("display", "none");
        $("#hidden13").css("display", "none");
        $("#hidden14").css("display", "none");

        $("#hidden11").css("display", "none");
        $("#hidden22").css("display", "none");
        $("#hidden33").css("display", "none");
        $("#hidden44").css("display", "none");
        $("#hidden171").css("display", "none");


        $("#m_btn1").css("color", "");
        $("#m_btn2").css("color", "");
        $("#m_btn3").css("color", "");
        $("#m_btn4").css("color", "");
        $("#m_btn5").css("color", "");
        $("#m_btn6").css("color", "");
        $("#m_btn7").css("color", "");
        $("#m_btn8").css("color", "");
        $("#m_btn9").css("color", "");
        $("#m_btn10").css("color", "");
        $("#m_btn17").css("color", "");

        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden5_1").css("display", "none");
        $("#hidden6_1").css("display", "none");
        $("#hidden7_1").css("display", "none");
        $("#hidden8_1").css("display", "none");
        $("#hidden9_1").css("display", "none");
        $("#hidden10_1").css("display", "none");
        $("#hidden12_1").css("display", "none");
        $("#hidden13_1").css("display", "none");
        $("#hidden14_1").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");

        $("#viewhidden1_1").css("color", "");
        $("#viewhidden1_2").css("color", "");
        $("#viewhidden1_3").css("color", "");
        $("#viewhidden2_1").css("color", "");
        $("#viewhidden2_2").css("color", "");
        $("#viewhidden2_3").css("color", "");
        $("#viewhidden3_1").css("color", "");
        $("#viewhidden3_2").css("color", "");
        $("#viewhidden3_3").css("color", "");
        $("#viewhidden4_1").css("color", "");
        $("#viewhidden4_2").css("color", "");
        $("#viewhidden4_3").css("color", "");
        $("#viewhidden17_1").css("color", "");
        $("#viewhidden17_2").css("color", "");
        $("#viewhidden17_3").css("color", "");

        $("#hidden15").css("display", "");
        $("#m_btn11").css("color", "#FFBB00");
        $("#hidden15_1").css("display", "");

        $("#hidden16").css("display", "none");
        $("#hidden16_1").css("display", "none");
    }
    else if (num=="16")
    {
        $("#hidden1").css("display", "none");
        $("#hidden2").css("display", "none");
        $("#hidden3").css("display", "none");
        $("#hidden4").css("display", "none");
        $("#hidden5").css("display", "none");
        $("#hidden6").css("display", "none");
        $("#hidden7").css("display", "none");
        $("#hidden8").css("display", "none");
        $("#hidden9").css("display", "none");
        $("#hidden10").css("display", "none");
        $("#hidden12").css("display", "none");
        $("#hidden13").css("display", "none");
        $("#hidden14").css("display", "none");

        $("#hidden11").css("display", "none");
        $("#hidden22").css("display", "none");
        $("#hidden33").css("display", "none");
        $("#hidden44").css("display", "none");
        $("#hidden171").css("display", "none");


        $("#m_btn1").css("color", "");
        $("#m_btn2").css("color", "");
        $("#m_btn3").css("color", "");
        $("#m_btn4").css("color", "");
        $("#m_btn5").css("color", "");
        $("#m_btn6").css("color", "");
        $("#m_btn7").css("color", "");
        $("#m_btn8").css("color", "");
        $("#m_btn9").css("color", "");
        $("#m_btn10").css("color", "");
        $("#m_btn17").css("color", "");

        $("#hidden1_1").css("display", "none");
        $("#hidden1_2").css("display", "none");
        $("#hidden1_3").css("display", "none");
        $("#hidden2_1").css("display", "none");
        $("#hidden2_2").css("display", "none");
        $("#hidden2_3").css("display", "none");
        $("#hidden3_1").css("display", "none");
        $("#hidden3_2").css("display", "none");
        $("#hidden3_3").css("display", "none");
        $("#hidden4_1").css("display", "none");
        $("#hidden4_2").css("display", "none");
        $("#hidden4_3").css("display", "none");
        $("#hidden5_1").css("display", "none");
        $("#hidden6_1").css("display", "none");
        $("#hidden7_1").css("display", "none");
        $("#hidden8_1").css("display", "none");
        $("#hidden9_1").css("display", "none");
        $("#hidden10_1").css("display", "none");
        $("#hidden12_1").css("display", "none");
        $("#hidden13_1").css("display", "none");
        $("#hidden14_1").css("display", "none");
        $("#hidden17_1").css("display", "none");
        $("#hidden17_2").css("display", "none");
        $("#hidden17_3").css("display", "none");

        $("#viewhidden1_1").css("color", "");
        $("#viewhidden1_2").css("color", "");
        $("#viewhidden1_3").css("color", "");
        $("#viewhidden2_1").css("color", "");
        $("#viewhidden2_2").css("color", "");
        $("#viewhidden2_3").css("color", "");
        $("#viewhidden3_1").css("color", "");
        $("#viewhidden3_2").css("color", "");
        $("#viewhidden3_3").css("color", "");
        $("#viewhidden4_1").css("color", "");
        $("#viewhidden4_2").css("color", "");
        $("#viewhidden4_3").css("color", "");
        $("#viewhidden17_1").css("color", "");
        $("#viewhidden17_2").css("color", "");
        $("#viewhidden17_3").css("color", "");

        $("#hidden15").css("display", "none");
        $("#m_btn11").css("color", "");
        $("#hidden15_1").css("display", "none");

        $("#hidden16").css("display", "");
        $("#hidden16_1").css("display", "");
    }
}


function getDatetimeString(date)
{
    let year = date.getFullYear();

    let month = (1 + date.getMonth()).toString();
    month = month.length > 1 ? month : '0' + month;

    let day = date.getDate().toString();
    day = day.length > 1 ? day : '0' + day;

    let hour = date.getHours().toString();
    hour = hour.length > 1 ? hour : '0' + hour;

    let minutes = date.getMinutes().toString();
    minutes = minutes.length > 1 ? minutes : '0' + minutes;

    let seconds = date.getSeconds().toString();
    seconds = seconds.length > 1 ? seconds : '0' + seconds;

    return year + '-' + month + '-' + day + ' ' + hour + ':' + minutes + ':' + seconds;
}

function getTimeDiff(dateString1, dateString2)
{
    let date1 = new Date(dateString1);
    let date2 = new Date(dateString2);
    let diff = date2.getTime() - date1.getTime();

    return diff / 1000;
}
