<?
define("_AtoZ_", TRUE); // 이 상수가 정의되지 않으면 각각의 개별 페이지는 별도로 실행될 수 없음
if (function_exists("date_default_timezone_set")) {
    date_default_timezone_set("Asia/Seoul");
}
$AtoZ['cookie_domain'] = "";
$AtoZ['charset'] = "utf-8";
$AtoZ['phpmyadmin_dir'] = $AtoZ['admin'] . "/pma/";
$AtoZ['token_time'] = 3; // 토큰 유효시간
?>