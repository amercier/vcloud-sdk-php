<?php

namespace VMware\VCloud\Http;

use \VMware\VCloud\Exception\InvalidArrayKey as InvalidArrayKey;

class SSLConfiguration
{
    const PARAM_VERIFY_PEER = 'ssl_verify_peer';
    const PARAM_VERIFY_HOST = 'ssl_verify_host';
    const PARAM_CAFILE      = 'ssl_cafile';

    public static $DEFAULTS = array(
        self::PARAM_VERIFY_PEER => false,
        self::PARAM_VERIFY_HOST => false,
        self::PARAM_CAFILE     => null,
    );

    protected $params;

    public function __construct(array $params = array())
    {
        $this->params = array();

        // Copy values into $this->params
        foreach ($params as $name => $value) {
            if (!array_key_exists($name, self::$DEFAULTS)) {
                throw new InvalidArrayKey('params', $name, self::$DEFAULTS);
            }
            $this->params[ $name ] = $value;
        }

        // Set missing parameters to their default values
        foreach (self::$DEFAULTS as $name => $defaultValue) {
            if (!array_key_exists($name, $this->params)) {
                $this->params[ $name ] = $defaultValue;
            }
        }
    }

    public function toArray()
    {
        return $this->params;
    }

    public static function getDefaultConfiguration()
    {
        return new self(self::$DEFAULTS);
    }
}
