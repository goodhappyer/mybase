<?php
/**
 * 前台页面基类
 *
 * @author andery
 */
class frontendh5pagelistAction extends frontendAction 
{
	var $_name;
	var $_mod;
	var $search_field; /*LIKE要查找的字段*/
	public function index()
	{
		$p = I ( "p" ) ? I ( "p" ) : 0;
		$map = $this->_search ();
		$map['status']=1;
		$r = $this->_mod->where ( $map )->field('info',true)->order ( "ordid" )->limit($p*20,20)->select ();
		foreach ($r as $k=>$v)
		{
			$r[$k]["h5url"]="http://".$_SERVER['HTTP_HOST']."/index.php?g=app&m=".$this->_name."&a=show&id=".$v['id'];
		}
		$this->json_echo ( 1, "！", $r ,1);
	}
	public function show()
	{
		$r=$this->_mod->where ( array("id"=>I('id')) )->find();
echo '<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=750px, initial-scale=0.48, maximum-scale=1">
	<title></title>
<style>
body,html
{
	background: #f5f5f5;
	width: 750px;
}
</style>
</head><body>';
		echo $r['info'];
echo '</body>
</html>';
	}
}