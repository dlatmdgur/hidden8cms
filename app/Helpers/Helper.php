<?php

namespace App\Helpers;

use App\Model\Account\AccountInfo;
use App\Model\Tables\Level;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Helper
{

	public static $tiers = [ 'GRAND', 'MAJOR', 'MINOR', 'MINI'];
	public static function gitHash()
	{
		return trim(exec('git log --pretty="%h" -n1 HEAD'));
	}

	public static function commitDatetime()
	{
		return trim(exec('git log -1 --graph --pretty=format:"%h" --date=short'));
	}

	public static function getLogDateNumber($logDate)
	{
		return str_replace('-', '', substr($logDate, 0, 10));
	}

	public static function getDate($date, $period)
	{
		$time = strtotime($date);
		$targetTime = $time + $period;
		return date("Y-m-d H:i:s", $targetTime);
	}

	public static function getPeriod($date, $targetDate)
	{
		$time = strtotime($date);
		$targetTime = strtotime($targetDate);
		return ($targetTime - $time);
	}

	// todo:: Excel file Load 로 변경 필요
	public static function userStates()
	{
		return [
			'닉네임변경', '정상', '탈퇴진행중', '정지', 'CS처리중',
			//'', '정상', '휴면', '3일정지', '7일정지', '30일정지', '영구정지',
		];
	}

	public static function getUserState($state)
	{
		$userStates = self::userStates();
		return $userStates[$state];
	}

	public static function marketType($type)
	{
		$markets = [
			0 => 'PC Dev',
			1 => 'Google Play',
			2 => 'Platform',
			3 => 'OneStore',
			4 => 'Mobilians',
		];
		return $markets[$type];
	}

	public static function marketTypeKor($type)
	{
		$markets = [
			0 => '개발',
			1 => '구글',
			2 => '플랫폼',
			3 => '원스토어',
			4 => '모빌리언스',
		];
		return $markets[$type];
	}

	public static function gameType()
	{
		return [
			0  => ['name' => '공통', 'eng' => 'All',],
			2  => ['name' => '블랙잭', 'eng' => 'BlackJack',],
			3  => ['name' => '바카라', 'eng' => 'Baccarat',],
			4  => ['name' => '바둑이', 'eng' => 'LowBadugi',],
			5  => ['name' => '하이로우', 'eng' => 'HiLowPoker',],
			6  => ['name' => '세븐포커', 'eng' => 'SevenPoker',],
			7  => ['name' => '홀덤', 'eng' => 'TexasHoldem',],
			8  => ['name' => '다이사이', 'eng' => 'TaiSai',],
			9  => ['name' => '오마하', 'eng' => 'OmahaHoldem',],
			11 => ['name' => '홀덤바둑이', 'eng' => 'BadugiHoldem',],
		];
	}

	public static function gameName($type)
	{
		$gameTypes = self::gameType();
		return $gameTypes[$type]['name'];
	}

	public static function gameSubType()
	{
		return [
			0 => ['name' => '칩', 'eng' => 'Chip',],
			1 => ['name' => '친구 칩', 'eng' => 'FriendChip',],
			2 => ['name' => '골드', 'eng' => 'Gold',],
			3 => ['name' => '친구 골드', 'eng' => 'FriendGold',],
		];
	}

	public static function gameResults()
	{
		return [
			0 => 'None',
			1 => 'Win',
			2 => 'Lose',
			3 => 'Draw',
			4 => 'Walkover',
			5 => 'SideWin',
			6 => 'NotPlay',

			// 하이로우
			10 => 'SwingWin',
			11 => 'HighWin',
			12 => 'LowWin',
		];
	}

	public static function gameResult($code)
	{
		$results = self::gameResults();
		return $results[$code];
	}


	public static function gameChannels()
	{
		return [
			2 => [
				'10000' => ['name' => '자유방', 'type' => 'gold', 'bet' => 100],
				'10001' => ['name' => '이코노미', 'type' => 'gold', 'bet' => 1000],
				'10002' => ['name' => '비즈니스', 'type' => 'gold', 'bet' => 5000],
				'10003' => ['name' => '퍼스트', 'type' => 'gold', 'bet' => 10000],

			],
			3 => [
				'10000' => ['name' => '자유방', 'type' => 'gold', 'bet' => 100],
				'10001' => ['name' => '이코노미', 'type' => 'gold', 'bet' => 1000],
				'10002' => ['name' => '비즈니스', 'type' => 'gold', 'bet' => 5000],
				'10003' => ['name' => '퍼스트', 'type' => 'gold', 'bet' => 10000],
			],
			4 => [
				'10000' => ['name' => '자유방', 'type' => 'chip', 'bet' => 100],
				'10001' => ['name' => '이코노미1', 'type' => 'chip', 'bet' => 100],
				'10002' => ['name' => '이코노미2', 'type' => 'chip', 'bet' => 200],
				'10003' => ['name' => '비즈니스1', 'type' => 'chip', 'bet' => 400],
				'10004' => ['name' => '비즈니스2', 'type' => 'chip', 'bet' => 600],
				'10005' => ['name' => '퍼스트1', 'type' => 'chip', 'bet' => 1000],
				'10006' => ['name' => '퍼스트2', 'type' => 'chip', 'bet' => 2000],
				'10007' => ['name' => '프리미엄1', 'type' => 'chip', 'bet' => 4000],
				'10008' => ['name' => '프리미엄2', 'type' => 'chip', 'bet' => 10000],
				'10009' => ['name' => '마스터', 'type' => 'chip', 'bet' => 40000],
				'10013' => ['name' => '친구', 'type' => 'chip', 'bet' => 100],
				'10014' => ['name' => '친구골드', 'type' => 'gold', 'bet' => 10],
				'10020' => ['name' => '골드1', 'type' => 'gold', 'bet' => 10],
				'10021' => ['name' => '골드2', 'type' => 'gold', 'bet' => 500],
				'10022' => ['name' => '골드3', 'type' => 'gold', 'bet' => 1000],
				'10023' => ['name' => '골드4', 'type' => 'gold', 'bet' => 3000],
				'10024' => ['name' => '골드5', 'type' => 'gold', 'bet' => 5000],
				'10025' => ['name' => '골드6', 'type' => 'gold', 'bet' => 10000],
				'10026' => ['name' => '골드7', 'type' => 'gold', 'bet' => 20000],
				'10027' => ['name' => '골드8', 'type' => 'gold', 'bet' => 30000],
				'10028' => ['name' => '골드9', 'type' => 'gold', 'bet' => 50000],
				'10029' => ['name' => '10만채널', 'type' => 'chip', 'bet' => 100000],
				'10030' => ['name' => '20만채널', 'type' => 'chip', 'bet' => 200000],
			],
			5 => [
				'10000' => ['name' => '자유방', 'type' => 'chip', 'bet' => 5000],
				'10001' => ['name' => '이코노미1', 'type' => 'chip', 'bet' => 20000],
				'10002' => ['name' => '이코노미2', 'type' => 'chip', 'bet' => 500000],
				'10003' => ['name' => '비즈니스1', 'type' => 'chip', 'bet' => 2000000],
				'10004' => ['name' => '비즈니스2', 'type' => 'chip', 'bet' => 5000000],
				'10005' => ['name' => '퍼스트1', 'type' => 'chip', 'bet' => 20000000],
				'10006' => ['name' => '퍼스트2', 'type' => 'chip', 'bet' => 500000000],
				'10007' => ['name' => '프리미엄1', 'type' => 'chip', 'bet' => 2000000000],
				'10008' => ['name' => '프리미엄2', 'type' => 'chip', 'bet' => 50000000000],
				'10009' => ['name' => '마스터', 'type' => 'chip', 'bet' => 200000000000],
				'10013' => ['name' => '친구', 'type' => 'chip', 'bet' => 30000000],
				'10014' => ['name' => '친구골드', 'type' => 'gold', 'bet' => 500],
				'10020' => ['name' => '골드1', 'type' => 'gold', 'bet' => 500],
				'10021' => ['name' => '골드2', 'type' => 'gold', 'bet' => 1000],
				'10022' => ['name' => '골드3', 'type' => 'gold', 'bet' => 3000],
				'10023' => ['name' => '골드4', 'type' => 'gold', 'bet' => 5000],
				'10024' => ['name' => '골드5', 'type' => 'gold', 'bet' => 10000],
				'10025' => ['name' => '골드6', 'type' => 'gold', 'bet' => 30000],
				'10026' => ['name' => '골드7', 'type' => 'gold', 'bet' => 50000],
				'10027' => ['name' => '골드8', 'type' => 'gold', 'bet' => 10],
				'10028' => ['name' => '6천억', 'type' => 'chip', 'bet' => 600000000000],
				'10029' => ['name' => '1조', 'type' => 'chip', 'bet' => 1000000000000],
			],
			6 => [
				'10000' => ['name' => '자유방', 'type' => 'chip', 'bet' => 5000],
				'10001' => ['name' => '이코노미1', 'type' => 'chip', 'bet' => 20000],
				'10002' => ['name' => '이코노미2', 'type' => 'chip', 'bet' => 500000],
				'10003' => ['name' => '비즈니스1', 'type' => 'chip', 'bet' => 2000000],
				'10004' => ['name' => '비즈니스2', 'type' => 'chip', 'bet' => 5000000],
				'10005' => ['name' => '퍼스트1', 'type' => 'chip', 'bet' => 20000000],
				'10006' => ['name' => '퍼스트2', 'type' => 'chip', 'bet' => 500000000],
				'10007' => ['name' => '프리미엄1', 'type' => 'chip', 'bet' => 2000000000],
				'10008' => ['name' => '프리미엄2', 'type' => 'chip', 'bet' => 50000000000],
				'10009' => ['name' => '마스터', 'type' => 'chip', 'bet' => 200000000000],
				'10013' => ['name' => '친구', 'type' => 'chip', 'bet' => 30000000],
				'10014' => ['name' => '친구골드', 'type' => 'gold', 'bet' => 500],
				'10020' => ['name' => '골드1', 'type' => 'gold', 'bet' => 500],
				'10021' => ['name' => '골드2', 'type' => 'gold', 'bet' => 1000],
				'10022' => ['name' => '골드3', 'type' => 'gold', 'bet' => 3000],
				'10023' => ['name' => '골드4', 'type' => 'gold', 'bet' => 5000],
				'10024' => ['name' => '골드5', 'type' => 'gold', 'bet' => 10000],
				'10025' => ['name' => '골드6', 'type' => 'gold', 'bet' => 30000],
				'10026' => ['name' => '골드7', 'type' => 'gold', 'bet' => 50000],
				'10028' => ['name' => '6천억', 'type' => 'chip', 'bet' => 600000000000],
				'10029' => ['name' => '1조', 'type' => 'chip', 'bet' => 1000000000000],
			],
			7 => [
				'10000' => ['name' => '루키자유', 'type' => 'gold', 'bet' => 10],
				'10001' => ['name' => '브론즈', 'type' => 'gold', 'bet' => 500],
				'10002' => ['name' => '실버', 'type' => 'gold', 'bet' => 2000],
				'10003' => ['name' => '골드', 'type' => 'gold', 'bet' => 5000],
				'10004' => ['name' => '플래티넘', 'type' => 'gold', 'bet' => 10000],
				'10005' => ['name' => '다이아몬드', 'type' => 'gold', 'bet' => 30000],
				'10006' => ['name' => '마스터', 'type' => 'gold', 'bet' => 50000],
				// '10007' => ['name' => '50000골드', 'type' => 'gold', 'bet' => 50000],
				//'10008' => ['name' => '20000골드', 'type' => 'gold', 'bet' => 20000],
				'10010' => ['name' => '자유방', 'type' => 'chip', 'bet' => 2000],
				'10011' => ['name' => '주니어', 'type' => 'chip', 'bet' => 50000],
				'10012' => ['name' => '시니어', 'type' => 'chip', 'bet' => 500000],
				'10013' => ['name' => '프로', 'type' => 'chip', 'bet' => 2000000],
				'10014' => ['name' => '시티', 'type' => 'chip', 'bet' => 20000000],
				'10015' => ['name' => '프리미어', 'type' => 'chip', 'bet' => 500000000],
				'10016' => ['name' => '네쇼널', 'type' => 'chip', 'bet' => 2000000000],
				'10020' => ['name' => '컨티넨탈', 'type' => 'chip', 'bet' => 50000000000],
			],
			8 => [
				'10000' => ['name' => '자유방', 'type' => 'gold', 'bet' => 10],
			],
			9 => [
				'10000' => ['name' => '500골드', 'type' => 'gold', 'bet' => 500],
				'10001' => ['name' => '1000골드', 'type' => 'gold', 'bet' => 1000],
				'10002' => ['name' => '3000골드', 'type' => 'gold', 'bet' => 3000],
				'10003' => ['name' => '5000골드', 'type' => 'gold', 'bet' => 5000],
				'10004' => ['name' => '10000골드', 'type' => 'gold', 'bet' => 10000],
				'10005' => ['name' => '30000골드', 'type' => 'gold', 'bet' => 30000],
				'10006' => ['name' => '50000골드', 'type' => 'gold', 'bet' => 50000],
				'10007' => ['name' => '10골드', 'type' => 'gold', 'bet' => 10],
				'10008' => ['name' => '20000골드', 'type' => 'gold', 'bet' => 20000],
			],
			11 => [
				'10000' => ['name' => '자유',		'type' => 'gold',	'bet' => 10		],
				'10001' => ['name' => '주니어',		'type' => 'gold',	'bet' => 100	],
				'10002' => ['name' => '시니어',		'type' => 'gold',	'bet' => 500	],
				'10003' => ['name' => '프로',		'type' => 'gold',	'bet' => 1000	],
				'10004' => ['name' => '시티',		'type' => 'gold',	'bet' => 5000	],
				'10005' => ['name' => '프리미어',	'type' => 'gold',	'bet' => 10000	],
				'10006' => ['name' => '내셔널',		'type' => 'gold',	'bet' => 30000	],
				'10007' => ['name' => '컨티넨탈',	'type' => 'gold',	'bet' => 50000	],
			],
		];

	}


	public static function channelInfo($gameType)
	{
		$channelInfo = self::gameChannels();
		return $channelInfo[$gameType];
	}

	public static function rankSubTypes()
	{
		return [
			0 => '일반',
			1 => '친구',
			2 => '골드대전',
			3 => '친구골드',
		];
	}

	public static function madeNames()
	{
		return [
			'Top' => '탑',
			'OnePair' => '원페어',
			'TwoPair' => '투페어',
			'Straight' => '스트레이트',
			'Flush' => '플러쉬',
			'FullHouse' => '풀하우스',
			'NONE' => '',
			'Triple' => '트리플',
			'FourCard' => '포카드',
			'StraightFlush' => '스트레이트플러쉬',
			'LoyalStraightFlush' => '로얄스트레이트플러쉬',
			'Base' => '베이스',
			'TwoBase' => '투베이스',
			'Made' => '메이드',
			'Third' => '써드',
			'Second' => '세컨드',
			'Golf' => '골프',
		];
	}

	public static function getMadeName($made)
	{
		$madeNames = self::madeNames();
		return $madeNames[$made];
	}

	public static function getChannels($gameType, $betType, $selChan = null)
	{
		$channelInfo = self::channelInfo($gameType);
		$channels = [];

		if ($selChan)
		{
			if (isset($channelInfo[$selChan]))
				return array($selChan);
		}

		foreach ($channelInfo as $index => $info) {
			if ($betType == 'all') {
				array_push($channels, strval($index));
			} else {
				if ($info['type'] == $betType) {
					array_push($channels, strval($index));
				}
			}
		}

		return $channels;
	}

	public static function channelName($gameType, $channel)
	{
		$channelInfo = self::channelInfo($gameType);
		//return $channelInfo[$channel]['name'];
		return numberToKorean($channelInfo[$channel]['bet']);
	}

	public static function levelsExp()
	{
		$levelsExp = [0,];
		$levels = Level::orderby('level', 'ASC')->get();
		foreach ($levels as $level) {
			array_push($levelsExp, $level->maxExp);
		}
		return $levelsExp;
	}

	public static function membersInfo()
	{
		$membersInfo = [
			0 => ['name' => '없음', 'refill_chip' => 10, 'refill_gold' => 1, 'time_bonus' => 1, 'rakeback' => 0],
			3110 => ['name' => '실버 멤버스', 'refill_chip' => 100000000000, 'refill_gold' => 3, 'time_bonus' => 2, 'rakeback' => 0.35],
			3111 => ['name' => '루비 멤버스', 'refill_chip' => 200000000000, 'refill_gold' => 1, 'time_bonus' => 0, 'rakeback' => 0],
			3112 => ['name' => '골드 멤버스', 'refill_chip' => 100000000000, 'refill_gold' => 5, 'time_bonus' => 4, 'rakeback' => 0.5],
			3113 => ['name' => '다이아 멤버스', 'refill_chip' => 200000000000, 'refill_gold' => 7, 'time_bonus' => 7, 'rakeback' => 0.65],
			3114 => ['name' => '플래티넘 멤버스', 'refill_chip' => 200000000000, 'refill_gold' => 1, 'time_bonus' => 0, 'rakeback' => 0],
			];

		return $membersInfo;
	}

//	public static function membersInfo()
//	{
//		$membersInfo = [0 => ['name' => '없음', 'refill_chip' => 10, 'refill_gold' => 2, 'time_bonus' => 1],];
//		$members = Member::orderby('id', 'ASC')->get();
//		foreach ($members as $member) {
//			$goldFreeCharge = ($member->goldFreeCharge == 0) ? 2 : $member->goldFreeCharge;
//			$timeBonusCount = ($member->timeBonusCount == 0) ? 1 : $member->timeBonusCount;
//			$membersInfo[$member->id] = ['name' => $member->memo, 'refill_chip' => 10, 'refill_gold' => $goldFreeCharge, 'time_bonus' => $timeBonusCount];
//		}
//		return $membersInfo;
//	}

	private static function getSuit($no)
	{
		$suit = [1 => "♤", 2 => "♢", 4 => "♡", 8 => "♧"];
		return $suit[$no];
	}

	private static function getRank($no)
	{
		$rank = [1 => "A", 2 => "2", 3 => "3", 4 => "4", 5 => "5", 6 => "6", 7 => "7", 8 => "8", 9 => "9", 10 => "10", 11 => "J", 12 => "Q", 13 => "K"];
		return $rank[$no];
	}

	public static function transCards($cards)
	{
		$cardList = json_decode($cards, true);
		if (is_null($cardList)) {
			return '';
		}
		$cardText = [];
		foreach ($cardList as $card) {
			array_push($cardText, self::getSuit($card['m_eCardSuits']) . self::getRank($card['m_eRank']));
		}

		return implode('', $cardText);
	}

	public static function getBaccaratNumber($cards)
	{
		$cardList = json_decode($cards, true);
		if (is_null($cardList)) {
			return '';
		}
		$sum = 0;
		foreach ($cardList as $card) {
			$sum += ($card['m_eRank'] >= 10) ? 10 : $card['m_eRank'];
		}
		return $sum % 10;
	}

	public static function numberToKorean($number)
	{
		if(intVal($number) == 0) return 0;
		if(intVal($number) < 1000) return $number;

		$isMinus = (intVal($number) < 0);
		$inputNumber = ($isMinus) ? (-1 * intVal($number)) : intVal($number);
		$unitWords = ['', '만', '억', '조', '경 '];
		$splitUnit = 10000;
		$splitCount = count($unitWords);
		$resultArray = [];
		$resultString = '';

		for ($i = 0; $i < $splitCount; $i++) {
			$unitResult = ($inputNumber % pow($splitUnit, $i + 1)) / pow($splitUnit, $i);
			$unitResult = floor($unitResult);
			if ($unitResult > 0) {
				$resultArray[$i] = $unitResult;
			}
		}

		for ($i = 0; $i <= max(array_keys($resultArray)); $i++) {
			if (!isset($resultArray[$i])) continue;
			$resultString = number_format($resultArray[$i]) . $unitWords[$i] . $resultString;
		}

		return trim(($isMinus) ? '-' . $resultString : $resultString);
	}

	public static function sender()
	{
		return [
			'0' => ['name' => 'ShopPurchaseItem', 'desc' => '[상점] 구매'],
			'-1' => ['name' => 'QuestReward', 'desc' => '[업무] 업무 달성 보상'],
			'-2' => ['name' => 'MembersAttendanceReward', 'desc' => '[멤버스] 멤버스 출석 보상'],
			'-3' => ['name' => 'CollectionReward', 'desc' => '[컬렉션 달성] 아바타 컬렉션 달성 보상'],
			'-4' => ['name' => 'MonthAttendanceEventRewrad', 'desc' => '[출석 체크] 출석 보상'],
			'-5' => ['name' => 'WeekAttendanceEventRewrad', 'desc' => '[이벤트] 7일 출석 보상'],
			'-6' => ['name' => 'FriendPresentGold', 'desc' => '[친구] 친구에게서 선물'],
			'-7' => ['name' => 'RouletteEventReward', 'desc' => '[룰렛] 룰렛 보상'],
			'-8' => ['name' => 'LevelUpReward', 'desc' => '[레벨 업] 레벨 업 보상'],
			'-9' => ['name' => 'FreeChargeVideoReward', 'desc' => '[동영상 시청] 동영상 시청 보상'],
			'-10' => ['name' => 'GemConsumePaybackEventReward', 'desc' => '[이벤트] 페이백 이벤트 보상'],
			'-11' => ['name' => 'FirstPurchaseDoubleGemEventReward', 'desc' => '[이벤트] 첫 보석 구매 보상'],
			'-12' => ['name' => 'PurchaseStartPackageEventReward', 'desc' => '[이벤트] 스타트팩 구매 보상'],
			'-13' => ['name' => 'DuplicateAvatarReward', 'desc' => '[아바타 보상] 중복 아바타 보상'],
			'-14' => ['name' => 'MembersAllAttendanceReward', 'desc' => '[멤버스] 기존 멤버스 출석 보상'],
			'-15' => ['name' => 'TimeBonusOneMoreChance', 'desc' => '[더보기 찬스] 더보기 찬스 보상'],
			'-16' => ['name' => 'FreeChipRefill', 'desc' => '[무료리필] 무료리필 보상'],
			'-17' => ['name' => 'CmsSorryReward', 'desc' => '장애 사과 보상'],
			'-18' => ['name' => 'CmsThanksReward', 'desc' => '감사 이벤트 보상'],
			'-19' => ['name' => 'TnkFreeChargeReward', 'desc' => '[무료충전소] 무료충전소 보상'],
			'-20' => ['name' => 'CouponReward', 'desc' => '[쿠폰 사용] 쿠폰을 사용'],
			'-21' => ['name' => 'OddEvenEventReward', 'desc' => '[이벤트] 홀짝 이벤트 보상'],
			'-22' => ['name' => 'LevelUpEvent', 'desc' => '[이벤트] 레벨 달성 이벤트 보상'],
			'-23' => ['name' => 'SaveGold1vs1', 'desc' => '[1대1대전] 적립된 보너스'],
			'-24' => ['name' => 'ChipGameEventGold', 'desc' => '[보너스] 판수 보너스 지급'],
			'-25' => ['name' => 'HoldemOverGold', 'desc' => '보유 한도 초과 금액'],
			'-26' => ['name' => 'Lottery', 'desc' => '[이벤트] 복권 아이템 보상'],
			'-27' => ['name' => 'BuyMembers', 'desc' => '[멤버스] 멤버스 가입 보상'],
			'-28' => ['name' => 'NewUser_Recommend', 'desc' => '[회원가입] 추천인 입력 보상'],
			'-90' => ['name' => 'EventCommon', 'desc' => '[이벤트] 이벤트 당첨'],
			'-98' => ['name' => 'RenewalGold', 'desc' => '[업데이트] 골드 개편'],
			'-99' => ['name' => 'MembersOverMoney', 'desc' => '[멤버쉽종료] 보유한도 초과'],
			'-100' => ['name' => 'SafeGoldExOverMoney', 'desc' => '[금고 강화권] 보유 한도 초과'],
			'-101' => ['name' => 'GmReward', 'desc' => '[운영자] 운영자 지급'],
			'-110' => ['name' => 'ClubRakeback', 'desc' => '[클럽] 클럽 마스터 보상'],
			'-111' => ['name' => 'ClubCoupon', 'desc' => '[클럽] 클럽원 보상'],
			'-204' => ['name' => 'RankRewardBadugi', 'desc' => '[랭킹전보상] 바둑이 랭킹전 보상'],
			'-205' => ['name' => 'RankRewardHigh', 'desc' => '[랭킹전보상] 하이로우 랭킹전 보상'],
			'-206' => ['name' => 'RankRewardSeven', 'desc' => '[랭킹전보상] 세븐포커 랭킹전 보상'],
			'-207' => ['name' => 'RankRewardHoldem', 'desc' => '[랭킹전보상] 홀덤 랭킹전 보상'],
			'-211' => ['name' => 'RankRewardBadugiHoldem', 'desc' => '[랭킹전보상] 랭킹전 보상'],
			'-1000' => ['name' => '', 'desc' => '[토너먼트] 취소로 인한 환불'],
		];
	}

	public static function reason($keyword)
	{
		$senders = self::sender();
		$reason = null;
		foreach ($senders as $sender) {
			if ($sender['name'] == $keyword) {
				$reason = $sender['desc'];
				break;
			}
		}

		return !is_null($reason) ? $reason : $keyword;
	}

	public static function reasonByKey($key)
	{
		if(-2000 < intval($key) && intval($key) < -1000) return '[토너먼트] ' . abs($key + 1000) . '등 보상';

		$senders = self::sender();
		$reason = null;
		if (isset($senders[$key])) {
			$reason = $senders[$key]['desc'];
		}

		return !is_null($reason) ? $reason : $key;
	}

	public static function adminLogType($type)
	{
		$logCases = [
			'test' => '테스트',
			'event' => '이벤트 보상',
			'maintenance' => '장애/오류 보상',
			'correction' => '오처리 정정',
			'claim' => '클레임',
			'admin' => '운영자 처리',
			'in_game' => '인게임',
			'abuze' => '어뷰징',
			'makeUp' => '짜고치기',
			'violation' => '운영정책위반',
			'illegalUse' => '도용의심',
		];

		return isset($logCases[$type]) ? $logCases[$type] : $type;
	}

	public static function adminTargets()
	{
		return [
			'chip' => '칩',
			'gold' => '골드',
			'safe_chip' => '금고 칩',
			'safe_gold' => '금고 골드',
			'gem' => '유료 보석',
			'event_gem' => '무료 보석',
		];
	}

	public static function adminActions()
	{
		return [
			'give' => '지급',
			'revoke' => '회수',
		];
	}

	public static function adminLogMenu($menu, $action)
	{
		$logMenus = [
			'member' => [
				'forceOut' => '[사용자] 강제종료',
				'edit' => '[사용자] 회원정보 변경',
				'ban' => '[사용자] 회원정보 일괄 변경',
				],
			'operation' => [
				'editChipGold'=> '[운영] 칩/골드 지급/회수',
				'editGem'=> '[운영] 보석 지급/회수',
				'editPresent' => '[운영] 가방 지급/회수',
				'editEffect' => '[운영] 효력 수정',
				'send' => '[운영] 대량발송',
				'ranking' => '[운영] 랭킹전 관리'
			],
		];
		return isset($logMenus[$menu][$action])? $logMenus[$menu][$action] : "미등록";
	}

	public static function csCategory()
	{
		return [
			0 => ['name' => '전체', 'eng' => 'All',],
			1 => ['name' => '회원정보', 'eng' => 'BlackJack',],
			2 => ['name' => '게임실행/설치', 'eng' => 'Baccarat',],
			3 => ['name' => '유료서비스', 'eng' => 'LowBadugi',],
			4 => ['name' => '게임', 'eng' => 'HiLowPoker',],
			5 => ['name' => '신고/제한', 'eng' => 'SevenPoker',],
			6 => ['name' => '이벤트', 'eng' => 'SevenPoker',],
			9 => ['name' => '기타', 'eng' => 'ETC',],
		];
	}

	public static function noticeCategory()
	{
		return [
			1 => ['name' => '공지',],
			2 => ['name' => '이벤트',],
			3 => ['name' => '업데이트',],
		];
	}

	public static function csStatus()
	{
		return [
			-1 => ['name' => '삭제',],
			0 => ['name' => '비노출',],
			1 => ['name' => '노출',],
			2 => ['name' => '예약',],
			3 => ['name' => '임시저장',],
		];
	}


	public static function swNumberFormat($money, $n=3)
	{
		$spl = explode('.', $money / pow(10, $n));

		echo number_format($spl[0]).'<span data-type="decimal" class="text-sm text-secondary">'.str_pad(empty($spl[1]) ? '' : $spl[1], $n, '0').'</span>';
	}

	/**
	 * return tester di
	 * @return array
	 */
	private static function testerDi() : array
	{
		return [
			'MC0GCCqGSIb3DQIJAyEAFefRCFJon8WPvvjU7bQ9eQMpuR4j7NWPPu3SLKzTjJY=',//qa-lsw
			'MC0GCCqGSIb3DQIJAyEAKwETDKrWeug60ll57OEd+ztzs4pqAKSomnwcyYE52vs=',//qa-lucia
			'MC0GCCqGSIb3DQIJAyEA3GPr71vzxVnUIj6RwOfGX8/L6orkVGdBGemu0J6oobo=',//client-smj
			'MC0GCCqGSIb3DQIJAyEA6SOYe9LbIOFdPExTAXIvGOyII/DyFdspXY34eOi9224=',//web-prmz
			'DONKDI1245789630avsderyasrmnmxn11223donk',//web-prmz-donk
			'MC0GCCqGSIb3DQIJAyEA8VXCq2Lo2Cn3TIrgwnGYlt4I9FuWNTmH2tf2oGQtcvY='
		];
	}

	/**
	 * return bool tester di
	 * @param string $di
	 * @return bool
	 */
	public static function isTesterDi(string $di) : bool
	{
		return in_array($di, self::testerDi());
	}

	/**
	 * return donk9 member id
	 * @return int
	 */
	public static function getMemberIdDonk9() : int
	{
		return 2280;
	}

	public static function getPagination($list, $page, $pageSize, $recordCnt)
	{
		$pageCnt = $recordCnt > 0 ? ceil($recordCnt / $list) : 1;
		$pageStart = ((ceil($page / $pageSize) - 1) * $pageSize) + 1;
		$pageEnd = ceil($page / 10) * 10;
		$pageEnd = $pageEnd > $pageCnt ? $pageCnt : $pageEnd;

		return [
			'page' => $page,
			'page_cnt' => $pageCnt,
			'page_size' => $pageSize,
			'page_start' => $pageStart,
			'page_end' => $pageEnd,
		];
	}

	/**
	 * 토너먼트 상태 반환
	 *
	 * @param int $num
	 * @return string
	 */
	public static function getTournamentStatus(int $num) : string
	{
		if($num == 99) return '취소';
		$list = ['서버실행전', '등록중', '등록마감', '입장가능', '진행중', '종료', '보상지급'];
		return $list[$num];
	}

	/**
	 * 티켓이벤트 아이템 아이디 반환
	 *
	 * @return array
	 */
	public static function getTicketSeedItemId() : array
	{
		return [
			3100, 3101, 3102, 3301, 3302
		  , 40101, 40102, 40201, 40202, 40301, 40302, 40401, 40402, 40501, 40502, 40601, 40602
		  , 40701, 40702, 40801, 40802, 40901, 40902, 41001, 41002, 41101, 41102, 41201, 41202
		];
	}

	/**
	 * 티켓이벤트 아이템명 반환
	 *
	 * @param int $itemId
	 * @return string
	 */
	public static function getTicketSeedItemName(int $itemId) : string
	{
		$definition = [
			3100 => '티켓이벤트25만',
			3101 => '티켓이벤트50만',
			3102 => '티켓이벤트100만',
			3301 => '티켓이벤트300만',
			3302 => '티켓이벤트500만',
			40101 => '2월 칩 티켓',
			40102 => '2월 골드 티켓',
			40201 => '3월 칩 티켓',
			40202 => '3월 골드 티켓',
			40301 => '4월 칩 티켓',
			40302 => '4월 골드 티켓',
			40401 => '5월 칩 티켓',
			40402 => '5월 골드 티켓',
			40501 => '6월 칩 티켓',
			40502 => '6월 골드 티켓',
			40601 => '7월 칩 티켓',
			40602 => '7월 골드 티켓',
			40701 => '8월 칩 티켓',
			40702 => '8월 골드 티켓',
			40801 => '9월 칩 티켓',
			40802 => '9월 골드 티켓',
			40901 => '10월 칩 티켓',
			40902 => '10월 골드 티켓',
			41001 => '11월 칩 티켓',
			41002 => '11월 골드 티켓',
			41101 => '12월 칩 티켓',
			41102 => '12월 골드 티켓',
			41201 => '1월 칩 티켓',
			41202 => '1월 골드 티켓'
		];
		if(!isset($definition[$itemId])) return '';
		return $definition[$itemId];
	}

	/**
	 * 토너먼트 티켓 아이템 아이디 반환
	 *
	 * @return array
	 */
	public static function getTicketTournamentItemId() : array
	{
		return [
			3200, 3201, 3202
		  , 3210, 3211, 3212
		  , 3220, 3221, 3222
		  , 3230, 3231, 3232
		  , 3240, 3241, 3242, 3245, 3243, 3244
		  , 3250, 3251
		];
	}

	/**
	 * 토너먼트 티켓 아이템명 반환
	 *
	 * @param int $itemId
	 * @return string
	 */
	public static function getTicketTournamentItemName(int $itemId) : string
	{
		$definition = [
			3200	=>	'5만 칩토너먼트 티켓',
			3201	=>	'25만 칩토너먼트 티켓',
			3202	=>	'50만 칩토너먼트 티켓',
			3240	=>	'100만 칩토너먼트 티켓',
			3241	=>	'150만 칩토너먼트 티켓',
			3242	=>	'250만 칩토너먼트 티켓',
			3243	=>	'500만 칩토너먼트 티켓',
			3244	=>	'1000만 칩토너먼트 티켓',
			3245	=>	'350만 칩토너먼트 티켓',

			3210	=>	'5.5만 칩 이벤트 티켓',

			3211	=>	'토너티켓_칩_기간제_10만',
			3212	=>	'토너티켓_칩_기간제_50만',

			3220	=>	'토너티켓_골드_2.5만(무제한)',
			3221	=>	'토너티켓_골드_10만(무제한)',
			3222	=>	'토너티켓_골드_50만(무제한)',

			3230	=>	'2.5만 골드토너먼트 티켓',
			3250	=>	'5만 골드토너먼트 티켓',
			3231	=>	'15만 골드토너먼트 티켓',
			3251	=>	'25만 골드토너먼트 티켓',
			3232	=>	'50만 골드토너먼트 티켓',

		];
		if(!isset($definition[$itemId])) return '';
		return $definition[$itemId];
	}


	/**
	 * 회원 검색 정보 반환
	 *
	 * @param string $searchType
	 * @param string $platform
	 * @param string $keyword
	 * @return AccountInfo|null
	 */
	public static function getAccountInfo($searchType, $platform, $keyword)
	{
		$accountInfo = null;
		if(!empty($keyword)) {
			if($searchType === 'nickname') {
				$accountInfo = AccountInfo::where(DB::raw('BINARY `nickname`'), $keyword)->first();
			} elseif($searchType === 'userSeq') {
				$accountInfo = AccountInfo::find($keyword);
			} elseif($searchType === 'email') {
				if($platform == '1') {
					$accountInfo = AccountInfo::where('google_email', $keyword)->orwhere('platform_id', $keyword)->first();
				} else {
					$accountInfo = AccountInfo::where('login_type', $platform)->where('google_email', $keyword)->first();
				}
			}
		}
		return $accountInfo;
	}

	/**
	 * 테이블 존재 여부 반환
	 *
	 * @param string $database
	 * @param string $table
	 * @return int
	 */
	public static function existsTable($database, $table)
	{
		return DB::connection('mysql')->table('Information_schema.TABLES')
			->where('TABLE_SCHEMA', '=', $database)
			->where('TABLE_NAME', '=', $table)->count();
	}

}
