# 슈퍼윈 CMS

## 슈퍼윈 모바일앱 CMS

- [DEV]
    - https://dev-cms.superwingame.com
- [PREVIEW]
    - https://prev-cms.superwingame.com
- [LIVE]
    - https://cms.superwingame.com

## GIT

> http://scm.nexnet.co.kr:50000/superwin/cms.git

## Symbolic Links

> FAQ, NOTICE JSON 파일 업로드

- [DEV]
  - ln -s /home/ec2-user/nexnet/dev-superwin-cms/storage/app/public /home/ec2-user/nexnet/dev-superwin-cms/public/storage
  - ln -s /home/ec2-user/nexnet/dev-superwin-cms/storage/app/public/publish /home/ec2-user/nexnet/dev-superwin-cms/public/publish/file
- [PREVIEW]
  - ln -s /home/ec2-user/nexnet/prev-superwin-cms/storage/app/public /home/ec2-user/nexnet/prev-superwin-cms/public/storage
  - ln -s /home/ec2-user/nexnet/prev-superwin-cms/storage/app/public/publish /home/ec2-user/nexnet/prev-superwin-cms/public/publish/file
- [LIVE]
  - ln -s /home/ec2-user/nexnet/superwin-cms/storage/app/public /home/ec2-user/nexnet/superwin-cms/public/storage
  - ln -s /home/ec2-user/nexnet/superwin-cms/storage/app/public/publish /home/ec2-user/nexnet/superwin-cms/public/publish/file

## Cron Jobs

- 매출 현황
  - 0 0 */1 * * /usr/bin/php /home/ec2-user/nexnet/superwin-cms/artisan gather:billing-statistics
- 아이템구매 현황
  - 0 0 */1 * * /usr/bin/php /home/ec2-user/nexnet/superwin-cms/artisan gather:buy-item-statistics
- 롤링공지 Redis publish
  - \* * * * *  /usr/bin/php /home/ec2-user/nexnet/superwin-cms/artisan publish:rolling-notice

## 기타사항

> 게임채널 변경 시 ***Helper*** 클래스 ***gameChannels*** 함수 내용 RoomInfo_{게임명}.xlsx 파일과 동기화
 
> 게임타입 ***Helper*** 클래스 ***gameType*** 함수 참조

> 우편출처 ***Helper*** 클래스 ***reasonByKey***, ***sender*** 함수 참조
 
> item_id
> - 2016 ~ 2019 : ConstantTemplate.xlsx 파일 참조
> - 그외 : GameItemInfoTable.xlsx 파일 참조
