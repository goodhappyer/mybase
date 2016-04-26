<style type="text/css">
table  {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: 1px;
	border-color: #666666;
	border-collapse: collapse;
}
table  th {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #dedede;
}
table  td {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #ffffff;
}
div {
	font-size: 14px;
	line-height: 28px;
	background-color: #DEDEDE;
	font-weight: bold;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: none;
	border-left-style: solid;
	border-top-color: #666666;
	border-right-color: #666666;
	border-bottom-color: #666666;
	border-left-color: #666666;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
}
p {
	margin: 3px;
	padding: 0px;
	float: left;
	width: 200px;
	line-height: 25px;
	background-color: #CCCCCC;
	text-align: center;
}
p a {
	color: #222;
	text-decoration: none;
}

</style>
<?php
$server="127.0.0.1";
$user="root";
$pwd="root";
$database="wwwed";
$connection=mysql_connect($server,$user,$pwd);
mysql_select_db($database,$connection);
mysql_query("set names utf8");
$result =mysql_query("show FULL tables",$connection);
while($r=mysql_fetch_array($result))
{
	echo '<p><a href="?tab='.$r[0].'">'.$r[0]."</a></p>";
}
$tab=isset($_REQUEST['tab'])?$_REQUEST['tab']:'';
if($tab){
	echo('<div style="clear:both;height:20px;width:100%;background:#fff;border:none">&nbsp;</div>');
	echo '<table width="100%" border="0"><tr><th colspan="6">'.$tab."</th><tr>";
	echo '<tr><th width="12%">序号</th><th width="12%">键</th><th width="12%">字段名</th><th width="12%">类型</th><th width="12%">长度</th><th width="12%">说明</th></tr>';
	$desc_r0 =mysql_query("SHOW FULL COLUMNS FROM ".$tab,$connection);
	$c=1;
	while($r1=mysql_fetch_array($desc_r0))
	{
		echo " <tr>";
		//print_r($r1);
		echo "<td>".$c."</td>";
		$c++;
		if($r1['Key']=="PRI")
		{
			echo "<td>主</td>";
		}
		else if($r1['Key']=="UNI")
		{
			echo "<td>唯</td>";
		}
		else if($r1['Key']=="MUL")
		{
			echo "<td>重</td>";
		}
		else
		{
			echo "<td></td>";
		}
		echo "<td>".$r1['Field']."</td>";
		echo "<td>".$r1['Type']."</td>";
		echo "<td>".findNum($r1['Type'])."</td>";
		echo "<td>".$r1['Comment']."</td>";
		echo " </tr>";
	}

	echo "</table>";
}
function findNum($str=''){
	$str=trim($str);
	if(empty($str)){return '';}
	$result='';
	for($i=0;$i<strlen($str);$i++){
		if(is_numeric($str[$i])){
			$result.=$str[$i];
		}
	}
	return $result;
}
?>