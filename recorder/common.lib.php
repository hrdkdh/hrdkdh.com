<?
if (!defined('_AtoZ_')) exit;

//브라우저 정보 로드
function getBrowserInfo() 
{ 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) { $platform = 'linux'; }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) { $platform = 'mac'; }
    elseif (preg_match('/windows|win32/i', $u_agent)) { $platform = 'windows'; }
    
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) { $bname = 'Internet Explorer'; $ub = "MSIE"; } 
    elseif(preg_match('/Firefox/i',$u_agent)) { $bname = 'Mozilla Firefox'; $ub = "Firefox"; } 
    elseif(preg_match('/Chrome/i',$u_agent)) { $bname = 'Google Chrome'; $ub = "Chrome"; } 
    elseif(preg_match('/Safari/i',$u_agent)) { $bname = 'Apple Safari'; $ub = "Safari"; } 
    elseif(preg_match('/Opera/i',$u_agent)) { $bname = 'Opera'; $ub = "Opera"; } 
    elseif(preg_match('/Netscape/i',$u_agent)) { $bname = 'Netscape'; $ub = "Netscape"; } 
    
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?' . join('|', $known).')[/ ]+(?[0-9.|a-zA-Z.]*)#';
    //if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    //}
    
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){ $version= $matches['version'][0]; }
        else { $version= $matches['version'][1]; }
    }
    else { $version= $matches['version'][0]; }
    
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
    return array('userAgent'=>$u_agent, 'name'=>$bname, 'version'=>$version, 'platform'=>$platform, 'pattern'=>$pattern);
}

//***********************************************************************************************  일반 함수 모음
//Ymd 형식으로 된 날짜를 바를 넣어 출력해 줌 - 김두환
function ymd_bar($Ymd) {
	$bar_ymd=substr($Ymd,0,4)."-".substr($Ymd,4,2)."-".substr($Ymd,6,2);
	return $bar_ymd;
}

//Ymd 형식으로 된 날짜를 한글을 넣어 출력해 줌 - 김두환
function ymd_hangul($Ymd) {
	$bar_ymd=substr($Ymd,0,4)."년 ".substr($Ymd,4,2)."월 ".substr($Ymd,6,2)."일";
	return $bar_ymd;
}

//Ymd 형식으로 된 날짜를 MD형태로 한글을 넣어 출력해 줌 - 김두환
function ymd_hangul_md($Ymd) {
	$bar_ymd=substr($Ymd,4,2)."월 ".substr($Ymd,6,2)."일";
	return $bar_ymd;
}

//한자리수 숫자의 앞에 0을 붙여 두자리로
function get_zero($str) {
	if (strlen($str)=="1") {
		$str="0".$str;
	}
	return $str;
}

//글자 자르기 펑션~~
function trim_str($lbl, $length) { //문자열 자르기
       preg_match('/([\x00-\x7e]|..)*/', substr($lbl,0,$length), $return);  //먼저 자르고 한글 아닌 것은 두글자씩 나머지는 영문기준 한 글자씩 처리한다.
 
      if ( $length < strlen($lbl) ) $return[0].="...";    //문자열이 길면 " ... " 을 붙인다.
      return $return[0];
}

//글자 자르기 펑션~~ for UTF-8
function trim_str_utf($str, $len, $tail='...'){ 
    $rtn = array(); 
    return preg_match('/.{'.$len.'}/su', $str, $rtn) ? $rtn[0].$tail : $str; 
}

// 한글 요일
function get_yoil($date, $full=0) 
{
    $arr_yoil = array ("일", "월", "화", "수", "목", "금", "토");

    $yoil = date("w", strtotime($date));
    $str = $arr_yoil[$yoil];
    if ($full) {
        $str .= "요일";
    }
    return $str;
}

// 영어 요일
function get_yoil_eng($date, $full=0) 
{
    $arr_yoil = array ("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");

    $yoil = date("w", strtotime($date));
    $str = $arr_yoil[$yoil];
    if ($full) {
        $str .= "요일";
    }
    return $str;
}

//파일 업로드 시 에러출력 함수 20130613 김두환
function AlertUploadErr($error)
{
	if ($error=="1") { 
		$msg="파일용량이 허용범위를 초과하였습니다. 첨부파일을 제외한 나머지 내용은 반영되었습니다.";
	}
	if ($error=="2") { 
		$msg="파일이 완전히 업로드되지 않았습니다. 첨부파일을 제외한 나머지 내용은 반영되었습니다.";
	}
	if ($error=="3") { 
		$msg="파일이 전송되지 않았습니다. 첨부파일을 제외한 나머지 내용은 반영되었습니다.";
	}

	echo "
	<script>
		alert('{$msg}');
	</script>
	";
}

//파일업로드시 파일이름을 변환하여 출력해 준다.
function getFileName($srcName, $cate) {
	$ext=substr(strrchr($srcName,"."),1); //확장자앞 .을 제거하기 위하여 substr()함수를 이용
	$ext=strtolower($ext);
	$srcName=str_replace(' ', '_', $srcName); //문서명칭을 변환한다.
	$RealName=$srcName;
	$UploadName=date('YmdHis')."_".md5(preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $srcName)).".".$ext;
	
	if ($cate=="v") {
		$Name=$RealName;
	} else if ($cate=="u") {
		$Name=$UploadName;
	}
	return $Name;
}

//리스트 하단 페이지 출력 함수 20130613 김두환
function printPaging($count, $current, $setRows)
{
	$url=$_SERVER[REQUEST_URI];
	$urlCheck=explode("?",$url);

	//기존 주소에 page변수가 붙어서 온 것이라면 삭제해줌
	if ($urlCheck[1]) { //get변수가 붙은 경우에만 체크하면 됨  
		$pageCheck=explode("&",$urlCheck[1]); 
		$url=$urlCheck[0]; //biz/crm/consult_list.php
		for ($i=0; $i<count($pageCheck); $i++) {
			if (substr($pageCheck[$i],0,4)!="page") {
				if ($countCheck==0) {
					$url.="?".$pageCheck[$i];
				} else {
					$url.="&".$pageCheck[$i];
				}
			$countCheck=$countCheck+1;
			}
		}
	}

	$urlCheck=explode("?",$url);
	if ($urlCheck[1]) { //get변수가 있다는 뜻
		$mark="&";
	} else { //get 변수가 없다면~
		$mark="?";
	}

	$current = (int) $current;
	if ($current < 1) { $current = 1; }

	$pages = (int) ceil($count / $setRows);

	echo '<ul id="paging">';
	if ($current > $pages) {
		echo "<li class=\"current\" onclick=\"location.href='".$url.$mark."page=1'\">?</li>";
		echo '</ul>';
		return;
	}

	$dec = (int) (($current - 1) / 10);

	if ($dec) {
		$page = ($dec - 1) * 10 + 1;
		echo "<li class=\"pager\" onclick=\"location.href='".$url.$mark."page={$page}'\">&lt;&lt;</li>";
	}

	for ($i = 1; $i <= 10; ++$i) {
		$page = $dec * 10 + $i;
		if ($page > $pages) break;
		echo $page == $current ?
			"<li class=\"current\">{$page}</li>" :
			"<li class=\"pager\" onclick=\"location.href='".$url.$mark."page={$page}'\">{$page}</li>";
	}

	if (($dec + 1) * 10 + 1 <= $pages) {
		$page = ($dec + 1) * 10 + 1;
		echo "<li class=\"pager\" onclick=\"location.href='".$url.$mark."page={$page}'\">&gt;&gt;</li>";
	}

	echo '</ul>';
}

// 세션변수 생성
function set_session($session_name, $value)
{
    session_register($session_name);
    // PHP 버전별 차이를 없애기 위한 방법
    $$session_name = $_SESSION["$session_name"] = $value;
}


// 세션변수값 얻음
function get_session($session_name)
{
    return $_SESSION[$session_name];
}

// 쿠키변수 생성
function set_cookie($cookie_name, $value, $expire)
{
    global $AtoZ;

    setcookie(md5($cookie_name), base64_encode($value), $AtoZ[server_time] + $expire, '/', $AtoZ[cookie_domain]);
}


// 쿠키변수값 얻음
function get_cookie($cookie_name)
{
    return base64_decode($_COOKIE[md5($cookie_name)]);
}

/*************************************************************************
**
**  SQL 관련 함수 모음
**
*************************************************************************/
// 기존 sql_query 함수를 변환
function sql_query($sql)
{
	global $connect_db;
	$result=mysqli_query($connect_db, $sql);
    return $result;
}

// 결과값에서 한행 연관배열(이름으로)로 얻는다.
function sql_fetch_array($result)
{
    $row=mysqli_fetch_array($result);
    return $row;
}

// 쿼리를 실행한 후 결과값에서 한행을 얻는다.
function sql_fetch($sql)
{
    $result=sql_query($sql);
    $row=mysqli_fetch_array($result);
    return $row;
}

function sql_escape($str)
{
	global $connect_db;
	return trim(mysqli_real_escape_string($connect_db, $str));
}

function sql_password($value)
{
    // mysql 4.0x 이하 버전에서는 password() 함수의 결과가 16bytes
    // mysql 4.1x 이상 버전에서는 password() 함수의 결과가 41bytes
    $row = sql_fetch(" select password('$value') as pass ");
    return $row[pass];
}

// 불법접근을 막도록 토큰을 생성하면서 토큰값을 리턴
function get_token()
{
    $token = md5(uniqid(rand(), true));
    set_session("ss_token", $token);

    return $token;
}

// POST로 넘어온 토큰과 세션에 저장된 토큰 비교
function check_token()
{
    set_session('ss_token', '');
    return true;

    /*
    // 세션에 저장된 토큰과 폼값으로 넘어온 토큰을 비교하여 틀리면 에러
    if ($_POST['token'] && get_session('ss_token') == $_POST['token']) {
        // 맞으면 세션을 지운다. 세션을 지우는 이유는 새로운 폼을 통해 다시 들어오도록 하기 위함
        set_session('ss_token', '');
    } else {
        alert_close('토큰 에러');
    }
    */
}

?>