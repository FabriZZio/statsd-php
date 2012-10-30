<?php
namespace StatsD;

class StatsD 
{
	/** @var Handler **/
	protected $handler;

    /** @var string */
    protected $namespace;

    /** @var Handler **/
    protected static $defaultHandler;

    /** @var string */
    protected static $defaultNamespace;

	public function __construct(Handler $handler) {
		$this->handler = $handler;
	}

	/** Convenience method for incrementing stat(s) **/
	public function increment($stats, $sampleRate = 1) {
		$this->update($stats, 1, $sampleRate);
	}

	/** Convenience method for decrementing stat(s) **/
	public function decrement($stats, $sampleRate = 1) {
        $this->update($stats, -1, $sampleRate);
    }

    /** Convenience method for updating a single stat's timing in milliseconds **/
    public function time($stat, $time, $sampleRate = 1) {
        $this->send(array($stat => "$time|ms"), $sampleRate);
    }

	/** Update count stat(s) **/
	public function update($stats, $delta = 1, $sampleRate = 1) 
	{
		if (!is_array($stats)) { $stats = array($stats); }
        $data = array();
        foreach($stats as $stat) {
            $data[$stat] = "$delta|c";
        }

        $this->send($data, $sampleRate);
	}

    /** Update gauge stat(s) **/
    public function gauge($stats, $delta = 1)
    {
        if (!is_array($stats)) { $stats = array($stats); }
        $data = array();
        foreach($stats as $stat) {
            $data[$stat] = "$delta|g";
        }

        $this->send($data);
    }

	/** Send data over UDP **/
    protected function send(array $data, $sampleRate = 1)
    {
    	// sample
        $data = $this->sample($data, $sampleRate);
        if (empty($data)) { return; }

        // failures in any of this should be silently ignored, this is only monitoring
        try {
            $fp = $this->handler->open();
            if (! $fp) { return; }
            foreach ($data as $stat => $value)
            {
                // use namespace when available
                if($this->getNamespace()) {
                    $stat = $this->getNamespace() . '.' . $stat;
                }
                $this->handler->write($fp, "$stat:$value");
            }
            $this->handler->close($fp);
        } catch (Exception $e) {
        }
    }

    /** Sample data by sampleRate **/
    protected function sample(array $data, $sampleRate) 
    {
    	// sampling
        $sampledData = array();

        if ($sampleRate < 1) {
            foreach ($data as $stat => $value) {
                if ((mt_rand() / mt_getrandmax()) <= $sampleRate) {
                    $sampledData[$stat] = "$value|@$sampleRate";
                }
            }
        } else {
            $sampledData = $data;
        }

        return $sampledData;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace ? $this->namespace : (self::$defaultNamespace ? self::$defaultNamespace : null);
    }

    public static function getInstance($handler = null) {
        if(!$handler) {
            if(self::$defaultHandler) {
                $handler = self::$defaultHandler;
            } else {
                throw new \Exception('No default StatsD Handler set...');
            }
        }
        return new self($handler);
    }

    public static function setDefaultNamespace($namespace) {
        self::$defaultNamespace = $namespace;
    }

    public static function setDefaultHandler(Handler $handler) {
        self::$defaultHandler = $handler;
    }
}