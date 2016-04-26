<?php
$string="";
$dir="./apitxt/";
$file=scandir($dir);
foreach($file as $v)
{
	if($v=='.' || $v=='..')
	{
		
	}
	else 
	{
		$string=$string.file_get_contents("./apitxt/".$v);
	}
};
$string=str_replace("127.0.0.1","192.168.1.185", $string);
$string=preg_replace("/\[\/.*?\]/","</div>" , $string);
$string=preg_replace("/\[.*?\]/","<div class='\$0'>" ,$string);
$string=str_replace("class='[", "class='", $string);
$string=str_replace("]'>", "'>", $string);
$string=preg_replace('@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@','<a target="_blank" href=$1>$1</a>($1)',$string);
$html=file_get_contents("template.html");
echo str_replace('{$html}', $string, $html);