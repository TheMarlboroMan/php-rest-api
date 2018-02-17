<?php
namespace Rest_api;

interface Api_instance {

	//Must return an array with the full path of the dependencies for this instance.
	public function get_app_dependencies_path();

	//Must return a function to handle the exceptions.
	public function create_exception_handler();

	//Must return the full path to the log file.
	public function get_full_log_path();

	//Must return the full path to the directory where api resource classes live.
	public function get_full_api_class_path();
}

