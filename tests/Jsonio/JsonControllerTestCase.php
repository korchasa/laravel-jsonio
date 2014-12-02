<?php namespace Jsonio;

abstract class JsonControllerTestCase extends \Illuminate\Foundation\Testing\TestCase
{
	public function setUp()
	{
		parent::setUp();
	}

	function assertResponseOk()
	{
		parent::assertResponseOk();
		$meta = $this->client->getResponse()->getData()->meta;
		$this->assertEquals(200, $meta->code);
		if(property_exists($meta, 'error_type'))
			$this->assertNull($meta->error_type);
		if(property_exists($meta, 'error_message'))
			$this->assertNull($meta->error_message);
	}

	function assertResponseStatus($code)
	{
		parent::assertResponseStatus($code);
		$this->assertEquals($code, $this->client->getResponse()->getData()->meta->code);
		$this->assertNotNull($this->client->getResponse()->getData()->meta->error_type);
		$this->assertNotNull($this->client->getResponse()->getData()->meta->error_message);
	}

	function assertObjectHasAttributes($attributes, $object)
	{
		$object_attrs = array_keys(get_object_vars($object));
		sort($attributes);
		sort($object_attrs);
		$this->assertEquals($attributes, $object_attrs);
	}

	function get($url, $params = [])
	{
		return $this->call("GET", $url, $params);
	}

	function post($url, $body_data = [])
	{
		return $this->call("POST", $url, $body_data);
	}

	public function createApplication()
	{
		$unitTesting = true;
		$testEnvironment = 'testing';
		return require __DIR__.'/../../../../../bootstrap/start.php';
	}
}
