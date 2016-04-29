<?php
/**
 * 前台控制器基类
 *
 * @author andery
 */
class frontendmessagelistAction extends frontendAction 
{
	var $_name;
	var $_mod;
	public function index()
	{
		$p = I ( "p" ) ? I ( "p" ) : 0;
		$map = $this->_search ();
		$map['to_id']=$this->visitor['id'];
		$r = $this->_mod->where ( $map )->order ( "id desc" )->limit($p*20,20)->select ();
		foreach ($r as $k=>$v)
		{
			$r[$k]["h5url"]="http://".$_SERVER['HTTP_HOST']."/index.php?g=app&m=".$this->_name."&a=show&id=".$v['id'];
		}
		$this->json_echo ( 1, "！", $r ,1);
	}
	public function count()
	{
		$r = $this->_mod->where(array("to_id"=>$this->visitor['id'],"status"=>1))->count();
		$this->json_echo ( 1, "！", $r);
	}
	public function show()
	{
		$this->_mod->where ( array("id"=>I('id')) )->save(array("status"=>2));
		$r = $this->_mod->where ( array("id"=>I('id')) )->find();
echo '<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=750px, initial-scale=0.48, maximum-scale=1">
	<title>Document</title>
<style>
body,html
{
	background: #f5f5f5;
	width: 750px;
}
</style>
</head><body>';
echo $r['content'];
echo '</body>
</html>';
	}
	public function delete()
	{
		$this->_mod->where ( array("id"=>array("IN",I('id'))) )->delete();
		$this->json_echo ( 1, "！");
	}
}