<?php
function I($name, $default = '', $filter = null, $datas = null) {
	foreach ( $_POST as $k => $v ) {
		if ($k == $name) {
			return $v;
		}
	}
	
	foreach ( $_GET as $k => $v ) {
		if ($k == $name) {
			return $v;
		}
	}
	
	foreach ( $_SESSION as $k => $v ) {
		if ($k == $name) {
			return $v;
		}
	}
	
	foreach ( $_COOKIE as $k => $v ) {
		if ($k == $name) {
			return $v;
		}
	}
	return null;
}