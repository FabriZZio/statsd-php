<?php
namespace StatsD;

class NodeJsHandler implements Handler
{
	/** @var string **/
	protected $host;

	/** @var int **/
	protected $port;

	public function __construct($host, $port = 8125) {
		$this->host = $host;
		$this->port = (int) $port;
	}

    public function open()
    {
    	return fsockopen('udp://' . $this->host, $this->port, $errno, $errstr);
    }

    public function write($resource, $data)
    {
    	fwrite($resource, $data);
    }

    public function close($resource) {
    	fclose($resource);
    }
}