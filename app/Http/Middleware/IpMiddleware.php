<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IpMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		// 유저 정보 셋팅.
		$uEmail = Auth::user()->email;
		$uIp	= $request->ip();

		// 필터 체크 여부. ( 기본 체크로 한다 )
		$isAllow = true;



		//
		// 보안이고 뭐고 풀러달라함 - 2023.11.22 : 황대표님 요청!
		//
		return $next($request);



		//
		// 아이피 체크.
		//
		// 1. 등록된 아이피 값이 있으면 IP 체크 시도한다.
		// 2. 접속 IP가 등록된 아이피 이면 다음 스탭 건너뛸 수 있도록 한다.
		//
		if (!empty(env('ALLOWED_IP')))
		{
			foreach (explode(';', env('ALLOWED_IP')) as $ip)
			{
				if ($match = preg_match("/".$ip."/i", $request->ip()))
				{
					$isAllow = false;
					break;
				}
			}
		}



		//
		// 허용할 계정 체크.
		//
		if ($isAllow)
		{
			// 허용할 계정정보가 셋팅되어 있으면...
			if (!empty(env('ALLOWED_EMAIL')) && in_array($uEmail, explode(';', env('ALLOWED_EMAIL'))))
				$isAllow = false;
		}



		//
		// 거부할 계정 체크.
		//
		if (!empty(env('DENIED_EMAIL')) && in_array($uEmail, explode(';', env('DENIED_EMAIL'))))
			$isAllow = true;



		//
		//
		//
		if (count(explode(env('APP_HOST'), $_SERVER['HTTP_HOST'])) > 1)
		{
			if (!empty(env('EXTERNAL_EMAIL')) && in_array($uEmail, explode(';', env('EXTERNAL_EMAIL'))))
				$isAllow = true;
		}



		//
		//
		//
		if ($isAllow)
		{
			Auth::logout();
			return redirect('/login');
		}


		return $next($request);
	}
}
