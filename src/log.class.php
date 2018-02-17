<?php
namespace Rest_api;

class Log {

	private static $file=null;

	public static function init($_path) {
		//TODO: Check and so on.
		self::$file=fopen($_path, "a");
	}

	public static function log($text) {

		fwrite(self::$file, "[".date("y-m-d h:i:s")."] : ".$text."\n");
	}

	public static function end() {

		fclose(self::$file);
	}
}
