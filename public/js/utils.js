function getFormattedDate(dateString)
{
    let date = Date.parse(dateString);
    let year = date.getFullYear();

    let month = (1 + date.getMonth()).toString();
    month = month.length > 1 ? month : '0' + month;

    let day = date.getDate().toString();
    day = day.length > 1 ? day : '0' + day;

    return year + '-' + month + '-' + day;
}

function getFormattedDatetime(dateString)
{
    let date = new Date(dateString);
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

function getDateNumber(dateString)
{
    return dateString.replace(/-/gi, "").substr(0,8);
}

function getTimeDiff(dateString1, dateString2)
{
    let date1 = new Date(dateString1);
    let date2 = new Date(dateString2);
    let diff = date2.getTime() - date1.getTime();

    return diff / 1000;
}

function getWeekDay(dateString)
{
    let date = Date.parse(dateString);
    let weekDay = {0: '일', 1: '월', 2: '화', 3: '수', 4: '목', 5: '금', 6: '토'};
    return weekDay[date.getDay()];
}

function getPercent(levelsExp, level, exp)
{
    let curExp = levelsExp[level];
    return Math.round(exp / curExp * 100) + '%';
}

function checkMembers(dateString, period)
{
    let startDate = Date.parse(dateString);
    let nowTimestamp =  new Date().getTime() ;
    let checkTimestamp = startDate.getTime() + (period * 1000);

    return checkTimestamp > nowTimestamp;
}

function membersEndDate(startDateString, period)
{
    let startDate = Date.parse(startDateString);
    let checkTimestamp = startDate.getTime() + (period * 1000);
    let endDate = new Date(checkTimestamp);

    return getDatetimeString(endDate);
}

function membersEndDays(startDateString, period)
{
    let startDate = Date.parse(startDateString);
    let checkTimestamp = startDate.getTime() + (period * 1000);
    let endDate = new Date(checkTimestamp);
    let today = new Date();

    return Math.floor((endDate.getTime() - today.getTime()) / (1000 * 60 * 60 * 24));
}

function mergeCells(table_id, cell)
{
    let selector = '#' + table_id + ' .' + cell;
    let selectObject = document.querySelectorAll(selector);
    // console.log('selectObject', selectObject.length);
    for (let i = 0; i < selectObject.length; i++) {
        let text = selectObject[i].innerHTML;
        // console.log('target', text);
        let rows = contains(selector, text);
        // console.log('rows count', rows.length);
        if (rows.length > 1) {
            // console.log('rows[0]', rows[0]);
            rows[0].setAttribute("rowspan", rows.length);
            for (let j = 1; j < rows.length; j++) {
                rows[j].remove();
            }
            i += rows.length - 1;
        }
    }
}

function contains(selector, text) {
    let elements = document.querySelectorAll(selector);
    return [].filter.call(elements, function(element){
        return RegExp(text).test(element.innerHTML);
    });
}

function numberFormat(x) {
    if (parseInt(x) === 0) {
        return 0;
    }
    let number = parseInt(x);
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function numberToKorean(number){
    if (parseInt(number) === 0) {
        return 0;
    }

    if (Math.abs(number) < 10000) {
        return numberFormat(number);
    }

    let isMinus      = (parseInt(number) < 0);
    let inputNumber  = (isMinus)?  (-1 * parseInt(number)) : parseInt(number);
    let unitWords    = ['', '만 ', '억 ', '조 ', '경 '];
    let splitUnit    = 10000;
    let splitCount   = unitWords.length;
    let resultArray  = [];
    let resultString = '0';

    for (let i = 0; i < splitCount; i++){
        let unitResult = (inputNumber % Math.pow(splitUnit, i + 1)) / Math.pow(splitUnit, i);
        unitResult = Math.floor(unitResult);
        if (unitResult > 0){
            resultArray[i] = unitResult;
        }
    }
    let maxIdx = findMaxIndex(resultArray);
    for (let i = 0; i <= maxIdx; i++){
        if (!resultArray[i]) continue;
        if (i === 0) resultString = '';
        if (parseInt(resultString) === 0) resultString = '';
        resultString = String(numberFormat(resultArray[i])) + unitWords[i] + resultString;
    }

    return (isMinus)? '-'+ resultString : resultString;
}

function findMaxIndex(values)
{
    let max = 0;
    values.forEach( function(element, index, array) {
        if (max < parseInt(index)) {
            max = parseInt(index);
        }
    });
    return max;
}

function getSuit(no)
{
    let suits = { 1: "♤", 2: "♢", 4: "♡", 8: "♧", };
    return suits[no];
}
function getRank(no)
{
    let ranks = { 1: "A", 2: "2", 3: "3", 4: "4", 5: "5", 6: "6", 7: "7", 8: "8", 9: "9", 10: "10", 11: "J", 12: "Q", 13: "K", };
    return ranks[no];
}
function transCards(cardList)
{
    let cardText = [];
    cardList.forEach(function(card) {
        cardText.push( getSuit(card['m_eCardSuits']) + getRank(card['m_eRank']));
    });

    return cardText.join(' ');
}

function trnasCardsFromJson(json)
{
    return (json !== '')? transCards(JSON.parse(json)) : '';
}

function fillSearchDate(period, start, end) {
    let date = new Date();
    let ago = new Date();
    ago.setDate(ago.getDate() - period);
    if (period === 1) {
        $('#'+start).val(getFormattedDate(date.toDateString()));
    } else {
        $('#'+start).val(getFormattedDate(ago.toDateString()));
    }
    $('#'+end).val(getFormattedDate(date.toDateString()));
}

function fillSearchDateTime(period, start, end) {
    let date = new Date();
    let ago = new Date();
    ago.setDate(ago.getDate() - period);
    if (period === 1) {
        $('#'+start).val(getFormattedDate(date.toDateString()) + ' ' + '00:00:00');
    } else {
        $('#'+start).val(getFormattedDate(ago.toDateString()) + ' ' + '00:00:00');
    }
    $('#'+end).val(getFormattedDate(date.toDateString()) + ' ' + '23:59:59');
}

function setSearchDateTime(period, start, end) {
    let date = new Date();
    let ago = new Date();
    if(period !== 1) ago.setDate(ago.getDate() - period);
    $(start).val(getFormattedDate(ago.toDateString()) + ' ' + '00:00:00');
    $(end).val(getFormattedDate(date.toDateString()) + ' ' + '23:59:59');
}

function postToUrl(path, params, target, method) {
    method = method || "post";
    target = target || null;

    let form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);
    if (target != null) {
        form.setAttribute("target", target);
    }

    let csrfToken = $('meta[name=csrf-token]').attr('content');
    let csrfField = document.createElement("input");
    csrfField.setAttribute("type", "hidden");
    csrfField.setAttribute("name", '_token');
    csrfField.setAttribute("value", csrfToken);
    form.appendChild(csrfField);

    for (let key in params) {
        let hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", key);
        hiddenField.setAttribute("value", params[key]);
        form.appendChild(hiddenField);
    }
    document.body.appendChild(form);
    form.submit();
    return false;
}

function showPreview(type, url) {
    let popup = window.open(url, "_preview_"+type, "toolbar=yes, scrollbars=yes, resizable=yes, top=150,left=150,width=1280, height=680");
}

function showPublish(type, url) {
    let popup = window.open(url, "_publish_"+type, "toolbar=yes, scrollbars=yes, resizable=yes, top=150,left=150,width=1280, height=680");
}

function makePagination(data, func_name) {
    let html = [];
    let disabled = '', active = '';
    disabled = data.page == 1 ? ' disabled' : '';
    if(!func_name) func_name = 'goPage';
    html.push('<li class="page-item' + disabled + '">');
    html.push('<span class="page-link" onclick="' + func_name + '(1);">First</span>');
    html.push('</li>');
    disabled = data.page_end / 10 <= 1 ? ' disabled' : '';
    html.push('<li class="page-item' + disabled + '">');
    html.push('<span class="page-link" onclick="' + func_name + '(' + (data.page_start - 1) + ');">Prev</span>');
    html.push('</li>');
    for(let i = data.page_start; i <= data.page_end; i++) {
        active = data.page == i ? ' active' : '';
        html.push('<li class="page-item' + active + '">');
        html.push('<span class="page-link" onclick="' + func_name + '(' + i + ');">' + i + '</span>');
        html.push('</li>');
    }
    disabled = data.page_end + 1 > data.page_cnt ? ' disabled' : '';
    html.push('<li class="page-item' + disabled + '">');
    html.push('<span class="page-link" onclick="' + func_name + '(' + (data.page_end + 1) + ');">Next</span>');
    html.push('</li>');
    disabled = data.page == data.page_cnt ? ' disabled' : '';
    html.push('<li class="page-item' + disabled + '">');
    html.push('<span class="page-link" onclick="' + func_name + '(' + data.page_cnt + ');">Last</span>');
    html.push('</li>');

    return html;
}
