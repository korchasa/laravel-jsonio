<?php namespace Jsonio;

class JsonRequest
{
	static function getRequestId()
	{
		static $id;
		if(!$id)
			$id = uniqid();
		return $id;
	}
}
