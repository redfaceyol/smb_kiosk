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
?>