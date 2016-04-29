<?php
/**
 * 前台用户基类
 * @author Administrator
 *
 */
class frontenduserAction extends frontendAction
{

	var $_name;
	var $_mod;
	var $_user_password_filed="password";
	var $_user_username_filed="username";
	var $_user_avatar_filed="img";
	var $_user_avatar_path='upload/img/';
	public function _initialize ()
	{
		parent::_initialize();
	}
	public function user_edit ()
	{
		$d = array ();
		foreach ($this->_mod->getDbFields() as $key => $val)
		{
			if (I($val) != '')
			{
				if ($val == $this->_user_password_filed)
				{
					$d[$val] = md5(I($val));
					$r = $this->_mod->where(array ("id" => $this->visitor['id']))->find();
					if (md5(I('oldpassword')) != $r[$this->_user_password_filed])
					{
						$this->json_echo(0,"旧密码失败！");
					}
				}
				else
				{
					$d[$val] = I($val);
				}
			}
		}
		$this->_mod->where(array ("id" => $this->visitor['id']))->save($d);
		$this->do_login($this->visitor['id']);
	}

	public function user_show ()
	{
		$this->json_echo(1,"操作成功！",$this->_mod->where(array ("id" => $this->visitor['id']))
			->relation(true)
			->find());
	}

	public function up_img_avatar ()
	{
		if (move_uploaded_file($_FILES["img"]["tmp_name"],$this->_user_avatar_path.$this->visitor['id'] . ".png"))
		{
			$this->_mod->where(array ('id' => $this->visitor['id']))->save(array ($this->_user_avatar_filed => 'http://' . $_SERVER['HTTP_HOST'] . '/'. $this->_user_avatar_path . $this->visitor['id'] . ".png"));
			$this->json_echo(1,"操作成功！",array ('img' => 'http://' . $_SERVER['HTTP_HOST'] . '/'.$this->_user_avatar_path. $this->visitor['id'] . ".png"));
		}
		else
		{
			$this->json_echo(0,"上传失败");
		}
	}

	public function login ()
	{
		$username = I("username");
		$password = md5(I("password"));
		$this->_mod->where(array ($this->_user_username_filed => $username ,$this->_user_password_filed => $password))->save(array ("last_time" => time()));
		$r = $this->_mod->where(array ($this->_user_username_filed => $username ,$this->_user_password_filed => $password ,"status" => 1))->find();
		if ($r)
		{
			$this->do_login($r['id']);
		}
		else
		{
			$this->json_echo(0,"用户名密码错误！",$r);
		}
		$this->json_echo(0,"用户名密码错误！",$r);
	}

	private function do_login ($id)
	{
		if (is_numeric($id))
		{
			if ($id > 0)
			{
				$hx = new Hx();
				$hx->openRegister(array ($this->_user_username_filed => $id ,$this->_user_password_filed => md5(strrev(md5(strrev($id))))));
				$r = $this->_mod->where(array ("id" => $id))->find();
				$r['token'] = $this->encrypt($r['id'] . '|' . $r['last_time']);
				$r[$this->_user_password_filed] = md5(strrev(md5(strrev($id))));
				$this->json_echo(1,"登录成功！",$r);
			}
			else
			{
				$this->json_echo(0,"无效用户！");
			}
		}
		$this->json_echo(0,"无效用户！");
	}
}
?>