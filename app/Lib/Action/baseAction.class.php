<?php
/**
 * 控制器基类
 *
 * @author andery
 */
class baseAction extends Action {
	protected function _initialize() {
		// 消除所有的magic_quotes_gpc转义
		Input::noGPC ();
		if (false === $setting = F ( 'setting' )) {
			$setting = D ( 'setting' )->setting_cache ();
		}
		C ( $setting );
	}
	/**
	 * 加密
	 *
	 * @param unknown $code        	
	 * @return string
	 */
	public function encrypt($code) {
		return base64_encode ( mcrypt_encrypt ( MCRYPT_RIJNDAEL_256, md5 ( "" ), $code, MCRYPT_MODE_ECB, mcrypt_create_iv ( mcrypt_get_iv_size ( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB ), MCRYPT_RAND ) ) );
	}
	/**
	 * 解密
	 *
	 * @param unknown $code        	
	 * @return string
	 */
	public function decrypt($code) {
		return mcrypt_decrypt ( MCRYPT_RIJNDAEL_256, md5 ( "" ), base64_decode ( $code ), MCRYPT_MODE_ECB, mcrypt_create_iv ( mcrypt_get_iv_size ( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB ), MCRYPT_RAND ) );
	}

}