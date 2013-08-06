<?php

namespace VMware\VCloud\Http;

class ProxyConfiguration
{
    const PARAM_HOST     = 'proxy_host';
    const PARAM_PORT     = 'proxy_port';
    const PARAM_USER     = 'proxy_user';
    const PARAM_PASSWORD = 'proxy_password';

    public static $DEFAULTS = array(
        self::PARAM_HOST     => null,
        self::PARAM_PORT     => null,
        self::PARAM_USER     => null,
        self::PARAM_PASSWORD => null,
    );

    protected $params;

    public function __construct(array $params = array())
    {
        $this->params = array();

        // Copy values into $this->params
        foreach ($params as $name => $value) {
            if (!array_key_exists($name, self::$DEFAULTS)) {
                throw new Exception\InvalidKey('params', $name, self::$DEFAULTS);
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
