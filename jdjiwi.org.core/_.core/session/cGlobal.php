<?php


class cGlobal {

	private static $value = array();


	public static function is($n) {
		return array_key_exists($n, self::$value);
	}


	public static function get($n, $d=null) {
		if(isset(self::$value[$n])) return self::$value[$n];
	    else return $d;
	}
	public static function get2($n, $k) {
		if(isset(self::$value[$n][$k])) return self::$value[$n][$k];
	    else return null;
	}


	public static function set() {
		switch(func_num_args()) {
			case 2:
				self::$value[func_get_arg(0)] = func_get_arg(1);
				break;

			case 3:
				self::$value[func_get_arg(0)][func_get_arg(1)] = func_get_arg(2);
				break;

			case 4:
				self::$value[func_get_arg(0)][func_get_arg(1)][func_get_arg(2)] = func_get_arg(3);
				break;

		}
	}


	public static function getParam($n, $p) {
		return get2(self::$value, $n, serialize($p));
	}
	public static function setParam($n, $p, $v) {
		self::$value[$n][serialize($p)] = $v;
	}


	public static function del() {
		switch(func_num_args()) {
			case 1:
				unset(self::$value[func_get_arg(0)]);
				break;

			case 2:
				unset(self::$value[func_get_arg(0)][func_get_arg(1)]);
				break;

			case 3:
				unset(self::$value[func_get_arg(0)][func_get_arg(1)][func_get_arg(2)]);
				break;

		}
	}

}

?>