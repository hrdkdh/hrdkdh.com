<?
//클래스 정의
class recorder
{
	var $ListCount=0;

	//리스트 출력
	function GetList($searchQuery="", $order="") {
		if ($searchQuery) {
			$search="WHERE ".$searchQuery;
		}
		if ($order) {
			$order="ORDER BY ".$order;
		}
		$query=" SELECT * FROM _tts_str_for_record {$search} {$order} ";
		$this->ListCount=mysqli_num_rows(sql_query($query));
		$result=sql_query($query);

		$ret = array();
		while ($data = mysqli_fetch_assoc($result)) {
			$ret[] = $data;
		}
		mysqli_free_result($result);

		return $ret;
	}

	//세부내용 로드
	function GetInfo($pagerNo) {
		$results=sql_fetch(" SELECT * FROM _tts_str_for_record WHERE no='{$pagerNo}'  ");
		return $results;
	}

	//설정한 페이지당 출력 수에 따라 Limit 걸어주는 함수
	function GetLimit($page, $setRows)
	{
		if ($page<1) { $page = 1; }
		$offset=($page-1)*$setRows;
		return " LIMIT {$offset},".$setRows;
	}
}
?>