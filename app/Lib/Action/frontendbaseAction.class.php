<?php
/**
 * 前台控制器基类,不要把业务逻辑写在这里
 * @author andery
 */
class frontendbaseAction extends basecommonAction {
	var $_search_field;/*要查找的字段like 多个字段用 |分开*/
	protected $visitor = null;
	public function _initialize() {
		parent::_initialize ();
		$access=C("ACCESS");
		if($this->token 
				|| 
				!($access[GROUP_NAME.'/'.MODULE_NAME.'/'.ACTION_NAME]===true
				||$access[GROUP_NAME.'/'.MODULE_NAME.'/*']===true
				||$access[GROUP_NAME.'/*/*']===true)
		)
		{
				$this->_init_visitor ();
		}
	}
    /**
     * 获取请求参数生成条件数组
     */
    protected function _search() {
        //生成查询条件
        $mod = D($this->_name);
        $map = array();
        foreach ($mod->getDbFields() as $key => $val) {
            if (substr($key, 0, 1) == '_') {
                continue;
            }
            if (I($val)!=NULL)
            {
                $map[$val] = I($val);
            }
        }
        if(I("keyword"))
        {
        	$map[$this->_search_field]=array ( "LIKE", '%' . I("keyword") . '%', "OR");
        }
        return $map;
    }
	/*输出格式化日期 ，字段尾为time*/
	protected function autotimeformat(&$r) {
		if (is_array ( $r )) {
			foreach ( $r as $k => $v ) {
				/* 后面是time结尾，则自动转化时间 */
				if (substr ( $k, strlen($k)-4,4 ) == 'time') {
					if (is_numeric ( $v )) {
						if($v>0)
						{
							$r [$k] = str_replace("00:00:00","", date ( "Y-m-d H:i:s", $v ));
						}
						else
						{
							$r[$k]='';
						}
					} else {
						$this->autotimeformat ( $r [$k] );
					}
				} else {
					$this->autotimeformat ( $r [$k] );
				}
			}
		}
	}
	/*查找时间段*/
	protected function _search_time($field)
	{
		$endtime=I("endtime")?I("endtime"):2147483646;
		$endtime=$endtime+24*60*60-1;
		$starttime=I("starttime")?I("starttime"):0;
		if($endtime<$starttime)
		{
			$T=$starttime;
			$starttime=$endtime;
			$endtime=$T;
		}
		return array($field=>array("between",array($starttime,$endtime)));
	}
	/**
	 * 输出前图片加网址
	 *
	 * @param unknown $array
	 */
	function _add_http_imgage(&$array) {
		if (is_array ( $array )) {
			foreach ( $array as $k => $v ) {
				$this->_add_http_imgage ( $array [$k] );
			}
		} else {
			$abc = substr ( $array, - 4 );
			if (strncasecmp ( $abc, ".jpg", 4 ) == 0 || strncasecmp ( $abc, ".png", 4 ) == 0 || strncasecmp ( $abc, ".bmp", 4 ) == 0 || strncasecmp ( $abc, ".gif", 4 ) == 0) {
				if (strncasecmp ( substr ( $array, 0, 4 ), "http", 4 ) == 0) {
				} else {
					$array = "http://".$_SERVER['HTTP_HOST'].$array;
				}
			}
		}
	}
	/**
	 * 手机接口输出
	 *
	 * @param number $code
	 *        	状态 1为正常，其它全为错误,
	 *        	401，未登录
	 *        	402,操作权限不足
	 * @param string $msg
	 *        	提示信息
	 * @param       have_img是否有图，如果有图就加网址
	 * @param arrat $result
	 *        	必须是数组
	 *
	 */
	public function json_echo($code = 0, $msg = '', $result = array(),$have_img=0) {
		if($have_img)
		{
			$this->_add_http_imgage($result);
		}
		$arr = array (
				"code" => $code,
				"msg" => $msg,
				"result" => $result
		);
		echo json_encode ( $arr );
		exit ( 0 );
	}
	private function _init_visitor() {
		if(is_numeric(I ( "token" )))
		{
			$id=(int)I( "token" );
			$this->visitor = D ( "user" )->where ( array ( "id" => $id ) )->find ();
			return ;
		}
		else 
		{
			$id_last_time = explode("|",$this->decrypt ( I ( "token" ) ));
			$id = ( int ) $id_last_time[0];
		}
		if ($id > 0) {
			/* 如果换成数字，就一定是正常用户 */
			$this->visitor = D ( "user" )->where ( array (
					"id" => $id ,'last_time'=>$id_last_time[1]
			) )->find ();
			if (! $this->visitor) {
				$this->json_echo ( 401, "账号异常需要登录" );
			}
		} else {
			$this->json_echo ( 401, "需要登录" );
		}
	}
}