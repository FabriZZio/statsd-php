<?php
namespace StatsD;

interface Handler 
{
	public function open();
	public function write($resource, $data);
	public function close($resource);
}
