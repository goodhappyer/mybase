<?php
class frontenditemAction extends frontendAction {
	var $_name;
	var $_mod;
	var $_filed="*";
	public function index()
	{
		if(!is_numeric(I("typeid")))
		{
			$typeid=0;
		}
		else 
		{
			$typeid=I("typeid");
		}
		$r=$this->_mod->where(array("typeid"=>$typeid,"status"=>1))->field($this->_filed)->select();
		$this->json_echo ( 1, '列表成功！' ,$r);
	}
	public function show_all()
	{
		$T=array();
		$T=$this->_mod->where(array("typeid"=>0,"status"=>1))->select();
		foreach($T as $k=>$v)
		{
			$this->get_next($v['id'],$T[$k]['item']);
		}
		$this->json_echo ( 1, '列表成功！' ,$T);
	}
	private function get_next($typeid,&$T=array())
	{
		$r=$this->_mod->where(array("typeid"=>$typeid,"status"=>1))->select();
		if($r)
		{
			$T=$r;
		}
		else 
		{
			return ;
		}
	}
}