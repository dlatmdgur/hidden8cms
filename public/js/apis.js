/**
 *
 *
 *
 */
$.fn.formAction = function (d)
{
	var _t_ = $(this);

	d = d == undefined ? {} : d;
	d.url = d.url == undefined ? _t_.data('url') : d.url;

	if (d.url == null ||
		d.url == '')
		return alert('empty url.');


	var data = new FormData();

	for (var i in d)
		data.append(i, d[i]);


	$.ajax({
			url: '/gameData/post',
			method: 'POST',
			data: data,
			dataType:'json',
			processData: false,
			contentType: false,
			cache: false,
			beforeSend : function(xhr)
			{
				xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
			},
			xhr: function()
			{
				var myXhr = $.ajaxSettings.xhr();
				if(myXhr.upload)
				{
					myXhr.upload.addEventListener(
						'progress',
						function (e)
						{
							if(e.lengthComputable)
								var prog = (e.loaded * 100) / e.total;
						},
						false
						);
				}
				return myXhr;
			},
			error: function (e)
			{
				if (e.responseJSON == undefined ||
					e.responseJSON.data == undefined)
					return false;

				_t_.trigger('callback', e.responseJSON.data);
			},
			success: function (e)
			{
				_t_.removeAttr('data-status');

				_t_.trigger('callback', e.data);
				_t_.trigger('paging', e.data);
			}
		});

	return this;
};


/**
 * Form Ajax 처리 함수.
 *
 * * 내부함수
 * - formAlert
 *		인자[0] : 이벤트가 발생한 selector
 *		인자[1] : 이벤트메시지를 출력할 flag
 *			flag = false : 체크에 실패했을 때.
 *			flag = mixmatch : 비교에 실패했을 때
 *
 */
$(document).on('submit', '[method-transfer="async"]', function (e, s)
{
	var _t_ = $(this);


	var formAlert = function (node, flag){ alert(node.attr('data-msg-'+flag)); node.focus(); _t_.data('status', 'true'); return false; };

	if (_t_.data('status') == 'false')
		return false;

	var nodes = _t_.find('input, textarea');


	for (var i in nodes)
	{
		if (!$.isNumeric(i))
			continue;


		if (s !== true && $.inArray(nodes.eq(i).attr('name'), ['page']) == 0)
			nodes.eq(i).val(1);

		if (nodes.eq(i).attr('data-check') === undefined && nodes.eq(i).attr('data-check') != '')
			continue;

		if (nodes.eq(i).attr('disabled') !== undefined)
			continue;


		switch (nodes.eq(i).attr('type'))
		{
			case 'number':
				if ($.trim(nodes.eq(i).val()) === '')
					return formAlert(nodes.eq(i), 'false');

				if (nodes.eq(i).attr('max') !== '' &&
					parseInt(nodes.eq(i).attr('max')) < parseInt(nodes.eq(i).val()))
					return formAlert(nodes.eq(i), 'max');
				break;

			case 'password':
				if ($.trim(nodes.eq(i).val()) === '')
					return formAlert(nodes.eq(i), 'mismatch');

				if (nodes.eq(i).attr('name') === 'inPasswdRe' &&
					$.trim(_t_.find('input[name=inPasswd]').val()) !== $.trim(nodes.eq(i).val()))
					return formAlert(nodes.eq(i), 'mismatch');
				break;

			case 'checkbox':
				if (!nodes.eq(i).is(':checked'))
					return formAlert(nodes.eq(i), 'false');
				break;

			default:
				if ($.trim(nodes.eq(i).val()) === '')
					return formAlert(nodes.eq(i), 'false');
				break;
		}
	}


	if ($(this).attr('data-confirm') != undefined)
		if (confirm($(this).attr('data-confirm')) === false)
			return false;

	_t_.attr('data-status', 'false');

	$.ajax(
		{
			url: _t_.attr('action'),
			method: _t_.attr('method'),
			data: new FormData(this),
			dataType:'json',
			processData: false,
			contentType: false,
			cache: false,
			beforeSend : function(xhr)
			{
				xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
			},
			xhr: function()
			{
				var myXhr = $.ajaxSettings.xhr();
				if(myXhr.upload)
				{
					myXhr.upload.addEventListener(
						'progress',
						function (e)
						{
							if(e.lengthComputable)
								var prog = (e.loaded * 100) / e.total;
						},
						false
						);
				}
				return myXhr;
			},
			error: function (e)
			{
				if (e.responseJSON == undefined ||
					e.responseJSON.data == undefined)
					return false;

				_t_.trigger('callback', e.responseJSON.data);
			},
			success: function (e)
			{
				_t_.removeAttr('data-status');

				_t_.trigger('callback', e);
				_t_.trigger('paging', e);
			}
		});
});

$(document).on('submit', '[method-transfer="async-download"]', function (e, s)
{
	var _t_ = $(this);


	var formAlert = function (node, flag){ alert(node.attr('data-msg-'+flag)); node.focus(); _t_.data('status', 'true'); return false; };

	if (_t_.data('status') == 'false')
		return false;

	var nodes = _t_.find('input, textarea');

	for (var i in nodes)
	{
		if (!$.isNumeric(i))
			continue;


		if (s !== true && $.inArray(nodes.eq(i).attr('name'), ['page']) == 0)
			nodes.eq(i).val(1);

		if (nodes.eq(i).attr('data-check') === undefined && nodes.eq(i).attr('data-check') != '')
			continue;

		if (nodes.eq(i).attr('disabled') !== undefined)
			continue;


		switch (nodes.eq(i).attr('type'))
		{
			case 'number':
				if ($.trim(nodes.eq(i).val()) === '')
					return formAlert(nodes.eq(i), 'false');

				if (nodes.eq(i).attr('max') !== '' &&
					parseInt(nodes.eq(i).attr('max')) < parseInt(nodes.eq(i).val()))
					return formAlert(nodes.eq(i), 'max');
				break;

			case 'password':
				if ($.trim(nodes.eq(i).val()) === '')
					return formAlert(nodes.eq(i), 'mismatch');

				if (nodes.eq(i).attr('name') === 'inPasswdRe' &&
					$.trim(_t_.find('input[name=inPasswd]').val()) !== $.trim(nodes.eq(i).val()))
					return formAlert(nodes.eq(i), 'mismatch');
				break;

			case 'checkbox':
				if (!nodes.eq(i).is(':checked'))
					return formAlert(nodes.eq(i), 'false');
				break;

			default:
				if ($.trim(nodes.eq(i).val()) === '')
					return formAlert(nodes.eq(i), 'false');
				break;
		}
	}


	if ($(this).attr('data-confirm') != undefined)
		if (confirm($(this).attr('data-confirm')) === false)
			return false;

	if (_t_.find('input[name=url]').length <= 0)
		_t_.append('<input type="hidden" name="url" value="'+_t_.data('host')+_t_.attr('action')+'" />');

	_t_.attr('data-status', 'false');


	let filename = _t_.find('input[name="filename"]').length > 0 ? _t_.find('input[name="filename"]').val() : 'noname.txt';

	$.ajax(
		{
			url: '/gameData/download',
			method: _t_.attr('method'),
			data: new FormData(this),
			xhrFields: {
				responseType: 'blob',
			},
			dataType:'binary',
			processData: false,
			contentType: false,
			cache: false,
			beforeSend : function(xhr)
			{
				xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
			},
			error: function (e)
			{
				if (e.responseJSON == undefined ||
					e.responseJSON.data == undefined)
					return false;

				_t_.trigger('callback', e.responseJSON.data);
			},
			success: function (e)
			{
				const a = document.createElement('a');
				a.href = window.URL.createObjectURL(e);
				a.download = filename;
				a.click();

				_t_.removeAttr('data-status');

				if (e.data != undefined)
					_t_.trigger('callback', e.data);
			}
		});
});

$(document).on('paging', '[method-transfer="async"]', function (e, d)
{
	let p = $(this).find('.pagination');

	if (p.length <= 0)
		return;

	p.html('');


	d.page = d.page == undefined ? 1 : d.page;

	let total	= Math.ceil(d.total / d.limit);

	let start	= (d.page - 5 > 0 ? d.page - 4 : 1);
	let end		= (d.page + 4 < total ? d.page + 4 : total);
	end			= d.page <= 4 && total >= 9 ? 9 : end;

	if (isNaN(end) || end <= 0)
		end = start;

	for (var i = start; i <= end; i++)
		p.append('<li class="page-item'+(i == d.page ? ' active' : '')+'"><a class="page-link">'+i+'</a></li>');
});



/**
 * 링크 이벤트 정의.
 *
 *
 */
$(document).on('click', '[method-href]', function ()
{
	let h = $(this).attr('method-href');
	if (h == undefined ||
		h == '')
		return false;

	document.location.href = h;
});



/**
 * 닫기 버튼 이벤트 핸들러.
 *
 */
$(document).on('click', '[method-event="close"], button[type="cancel"]', function (e)
{
	e.preventDefault();

	$(this).parents('ul').eq(0).css('display', 'none');
	return false;
});

$(document).on('click', '[method-event="parent-close"], button[type="parent-cancel"]', function (e)
{
	e.preventDefault();
	$(this).parent().remove();
	return false;
});


/**
 * 삭제 버튼 이벤트 핸들러.
 *
 *
 */
$(document).on('click', '[method-event="remove"]', function (e)
{
	e.preventDefault();
	return $(this).remove();
});


/**
 * Javascript indicator...
 *
 *
 */
$.fn.indicator = function (txt)
{
	var _className_ = 'layer_loading';
	var _tag_ = '<div class="'+ _className_ +'" style="position:fixed;z-index:999999;top:0;left:0;right:0;bottom:0;background:#2b2b2b;background:url(/images/bg_layer.png);color:#fff;cursor:wait;"><span class="inner_layer" style="position:absolute;top:50%;left:50%;width:60px;height:60px;margin:-30px 0 0 -30px;background:url(/images/loading.png);background-position:0 0;background-repeat:no-repeat;text-align:center;-webkit-animation:load 1.4s infinite linear;animation:load 1.4s infinite linear;-webkit-transform:translateZ(0);-ms-transform:translateZ(0);transform:translateZ(0);"></span><span class="inner_txt" style="position:absolute;top:50%;left:50%;width:60px;height:60px;margin:-30px 0 0 -30px;font-weight:300;font-size:11px;text-align:center;line-height:60px;color:#fff;">'+ (typeof(txt) === 'undefined' ? '' : txt) +'</span></div>';

	($(this).find('.'+_className_).length > 0 ?
		(typeof(txt) === 'undefined' || txt === false ? $('.'+_className_).remove() : $('.'+_className_+' > .inner_txt').text(txt)) :
		($(this).prop('tagName') === undefined ?
			$(this).find('body').append(_tag_) :
			(txt == '' ? $('.'+_className_+' > .inner_txt').text(txt) : $(this).append(_tag_))
		)
	);
};



/**
 * Single Uploader.
 *
 */
$.fn.uploader = function (cb)
{
	// 이미지 업로드 노드.
	var _t_ = $(this);

	// 업로드에 사용할 키값.
	var name = _t_.data('name');
	// 업로드할 파일 종류.
	var accept = _t_.data('accept');

	// 필수값 무결성 체크.
	if (name == undefined)
		return;

	if (accept == undefined)
		accept = '*/*';

	// 파일 노드가 없다면...
	if (_t_.find('input[type="file"]').length <= 0)
		_t_.append('<input type="file" name="'+name+'" accept="'+accept+'" />');

	// 파일 노드 선택.
	var i = _t_.find('input[type="file"]');



	//
	// 업로드 노드 이벤트 정의.
	//
	//

	// 초기화.
	i.on('reset', function ()
	{
		$(this).val('');

		_t_.find('img').remove();
		_t_.removeClass('drag');
	});
	i.on('dragover', function ()
	{
		_t_.addClass('drag');
	});
	i.on('dragleave', function ()
	{
		$(this).trigger('reset');
	});

	i.on('change', function (e)
	{
		var f = e.target.files;

		if (f && f[0])
		{
			var rs = new FileReader();
			rs.readAsDataURL(f[0]);
			rs.onload = function (e)
			{
				_t_.find('img').remove();
				_t_.append('<img src="'+e.target.result+'" width="100%">');

				if (typeof cb == 'function')
					cb();
			};
		}
		else
			_t_.trigger('reset');
	});
};



/**
 * Clipboard function.
 *
 * @param mixed te 클립할 내용을 담은 노드명 ( 타입에 따라 다르게 처리 )
 */
$.fn.clipBoard = function (te)
{
	$(this).on('click', function ()
	{
		var _t_;

		switch (typeof(te))
		{
			case 'object':
				_t_ = te;
				break;
			case 'string':
				if (te.split('.').length > 1 || te.split('#').length > 1)
					_t_ = $(te).length > 0 ? $(te) : _t_.find(te);
				else
				{
					switch (te)
					{
						case 'prev':	_t_ = $(this).prev();	break;
						case 'next':	_t_ = $(this).next();	break;
						case 'parent':	_t_ = $(this).parent();	break;
					}
				}
				break;

			case 'undefined':
			case undefined:	_t_ = $(this);	break;
		}

		if (typeof(_t_) !== 'object')
			return false;

		var text = '';
		switch (_t_.prop('tagName'))
		{
			case 'BUTTON':
				text = _t_.attr('data-clipboard');
				break;

			case 'INPUT':
			case 'TEXTAREA':
				text = _t_.val();
				break;

			default:
				text = _t_.text();
				break;
		}

		var e = $('<input type="text" style="position:absolute; top:0; left:0; border:0; width:1px; height:1px; padding:0; color:transparent; background-color:transparent; z-index:0;" />');
		e.val(text);

		$('body').append(e);
		e.select().delay(10).queue(function (){ $(this).clearQueue().remove(); });;

		if (navigator.appName === 'Microsoft Internet Explorer')
		{
			window.clipboardData.setData('Text', text);
			alert('Copy to clipboard.\n\n[ '+window.clipboardData.getData('Text')+' ]');
		}
		else
		{
			document.execCommand('copy');
			alert('Copy to clipboard.\n\n[ '+text+' ]');
		}
	});
};



/**
 * String형 확장.
 *
 *
 */
String.prototype.number_format = function(m)
{
	let n = this.split('.');

	n[0] = n[0].replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
	if (n[1] != undefined)
		n[1] = n[1].substr(0, (m == undefined ? 3 : m));

	return n.join('.');
};



Date.prototype.format = function (x)
{
	y = typeof(y) === 'object' ? y : this;

	let z = {
		Y: y.getFullYear(),
		m: y.getMonth() + 1,
		d: y.getDate(),
		H: y.getHours(),
		i: y.getMinutes(),
		s: y.getSeconds(),
		z: y.getMilliseconds()
	};

	// 2 space
	x = x.replace(/(m+|d+|H+|i+|s+)/g, function(v){ return eval('z.' + v.slice(-1)).toString().padStart(2, '0'); });

	// 3 space
	x = x.replace(/(z+)/g, function(v){ return eval('z.' + v.slice(-1)).toString().padStart(3, '0'); });

	// 4 space
	return x.replace(/(Y+)/g, function(v){ return y.getFullYear().toString(); });
};



/**
 * 부트스트랩 이벤트 추가.
 *
 * @var object
 */
$(document).on('click', '.nav-item', function ()
{
	const t = $(this);

	t.children().addClass('active');
	t.siblings().each(function (n, e){ $(this).children().removeClass('active'); });

	$('[menu-tab]').each(function ()
	{
		if (t.data('tab') == $(this).attr('menu-tab'))
		{
			$(this).show(0);

			if ($(this).find('form').data('autoload') == 1)
				$(this).find('form').eq(0).trigger('submit');
		}
		else
			$(this).hide(0);
	});
});

$(document).on('click', '.pagination .page-item', function ()
{
	const t = $(this);
	const p = t.parent();
	const f = t.parents('form');

	f.find('input[name="page"]').val(t.text());

	t.parents('form').trigger('submit', true);
});


/**
 * NUMBER 형 COMMA 추가 프로토타입 확장.
 *
 * @var function
 */
Number.prototype.addComma = function (m)
{
	m = m > 0 ? m : 0;
	let a = this.toString().split(".");
	a[0] = a[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

	if (m > 0)
	{
		if (a[1] == undefined)
			a[1] = '';

		a[1] = a[1].toString().substr(0, m).padEnd(m, '0');
	}
	else if (a[1] != undefined)
		delete a[1];

	return a.join(".");
}