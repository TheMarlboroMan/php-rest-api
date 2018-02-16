<?php
namespace Rest_api;

class Config
{
	const	DEFAULT_CLASSNAME_PREFIX="Api_";
	const	DEFAULT_FILE_EXTENSION=".class.php";
	const	DEFAULT_FILE_PREFIX="api_";

	public	$absolute_path=null;
	public	$class_namespace=null;
	public	$classname_prefix=null;
	public	$file_extension=null;
	public	$file_prefix=null;

	public function __construct($_ap=null, $_ns=null, $_p=self::DEFAULT_CLASSNAME_PREFIX, $_e=self::DEFAULT_FILE_EXTENSION, $_fp=self::DEFAULT_FILE_PREFIX)
	{
		$this->absolute_path=$_ap;
		$this->class_namespace=$_ns;
		$this->classname_prefix=$_p;
		$this->file_extension=$_e;
		$this->file_prefix=$_fp;
	}
};
