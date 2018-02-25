<?php
namespace Rest_api;

class Api_bootstrap {

	private static $api_config=null;

	public static function set_api_config($path) {

		self::$api_config=new \RET\Tools\Ini_config($path);
	}

	public static function get_api_config() {

		return self::$api_config;
	}

	public static function run(Api_instance $instance) {

		Log::init($instance->get_full_log_path());

		set_error_handler(function ($err_severity, $err_msg, $err_file, $err_line, array $err_context) {
			if($err_severity!==E_DEPRECATED) 
				throw new Exception("Api error handler (".$err_severity."): ".$err_msg.' ['.$err_file.':'.$err_line.']', $err_severity);
		});

		register_shutdown_function(function () {
			$error=error_get_last();
			if($error['type']===E_ERROR) {
				//TODO: 500??... As in a magic number?
				http_response_code(500);
				$msg='Something terrible happened in '.$error['file'].' '.$error['line'].' : '.$error['message'];
				Log::log($msg);
				Log::end();
				die();
			}
		});

		$api_response=null;
		$api_factory=new \Rest_api\Factory();

		$request_type=isset($_GET['type']) ? strtolower($_GET['type']) : null;
		$request_method=strtolower($_SERVER['REQUEST_METHOD']);

		try{
			//TODO: Check content-type of request input???
			$request_input=file_get_contents("php://input");

			//TODO: Better to get the raw query string?.
			$request_get=$_GET;
			$request_headers=new\Rest_api\Request_headers();

			//TODO: LOG EVERYTHING, INCLUDING GET AND HEADERS...!!!!
			Log::log("requesting ".$request_method.":".$_SERVER['REQUEST_URI']." with [".$request_input."]");

			$config=new \Rest_api\Config($instance->get_full_api_class_path(), "\\OOT\\");
			$api_resource=$api_factory->get_resource($request_type, $config);

			//Load application specific functions and modules.
			foreach($instance->get_app_dependencies_path() as $path) {
				require_once($path);
			}

			$dispatcher=new \Rest_api\Dispatcher($instance->create_exception_handler());
			$dispatcher->dispatch($request_method, $api_resource, $request_input, $request_headers, $request_get)->resolve_response();
		}
		catch(\Rest_api\Api_exception $e) {
			Log::log($e->getMessage().':'.$e->get_log_info());
			\Rest_api\Response::get_error_response($e)->resolve_response();
		}
		catch(\Exception $e){
			Log::log($e->getMessage());
			\Rest_api\Response::get_error_response($e)->resolve_response();
		}

	}

}
