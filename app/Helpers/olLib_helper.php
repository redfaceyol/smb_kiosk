<? 

// 경고메세지를 경고창으로
function alert($msg='', $url='') {
	$app_config = config('App');

	if (!$msg) $msg = '올바른 방법으로 이용해 주십시오.';

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$app_config->charset."\">";
	echo "<script type='text/javascript'>alert('".$msg."');";
	if ($url)
		echo "location.replace('".$url."');";
	else
		echo "history.go(-1);";
	echo "</script>";
	exit;
}

// 경고메세지 출력후 창을 닫음
function alert_close($msg) {
	$app_config = config('App');

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$app_config->charset."\">";
	echo "<script type='text/javascript'> alert('".$msg."'); window.close(); </script>";
	exit;
}

// 경고메세지만 출력
function alert_only($msg) {
	$app_config = config('App');

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$app_config->charset."\">";
	echo "<script type='text/javascript'> alert('".$msg."'); </script>";
	exit;
}

function alert_continue($msg) {
	$app_config = config('App');

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$app_config->charset."\">";
	echo "<script type='text/javascript'> alert('".$msg."'); </script>";
}

function indentation($depth) {
	$returnVal = "";

	if($depth > 1) {
		$returnVal = "<span style=\"display:inline-block; margin:0 5px 0 ".(($depth-1)*($depth-1)*5)."px\">└</span>";
	}
	return $returnVal;
}

//이미지 함수
//비율 유지하여 최적 크기 계산
function colImageSize($in_srcWidth, $in_srcHeight, $in_trgWidth, $in_trgHeight) {
	if($in_srcWidth > $in_trgWidth) {
    $ratio = $in_trgWidth / $in_srcWidth;
    $trgWidth = $in_srcWidth * $ratio;
    $trgHeight = $in_srcHeight * $ratio;
  }
  if($trgHeight > $in_trgHeight) {
    $ratio = $in_trgHeight / $trgHeight;
    $trgWidth = $trgWidth * $ratio;
    $trgHeight = $trgHeight * $ratio;
  }
  return array($trgWidth, $trgHeight);
}

//파일용량 변환
function formatBytes($bytes, $precision = 2) {
	$units = array('B', 'KB', 'MB', 'GB', 'TB');
	 
	$bytes = max($bytes, 0);
	$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
	$pow = min($pow, count($units) - 1);
	 
	$bytes /= pow(1024, $pow);
	 
	return round($bytes, $precision) . ' ' . $units[$pow];
}
?>