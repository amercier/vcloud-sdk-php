<?php

namespace VMware\VCloud\Http;

class ProxyConfiguration
{
    public static const PARAM_HOST     = 'proxy_host';
    public static const PARAM_PORT     = 'proxy_port';
    public static const PARAM_USER     = 'proxy_user';
    public static const PARAM_PASSWORD = 'proxy_password';

    public static const DEFAULTS = array(
        self::PARAM_HOST     => null,
        self::PARAM_PORT     => null,
        self::PARAM_USER     => null,
        self::PARAM_PASSWORD => null,
    );

    protected $params;

    public function __construct(array $params)
    {
        $this->params = array();

        // Copy values into $this->params
        foreach ($params as $name => $value) {
            if (!array_key_exists($name, self::PARAMS)) {
                throw new  ..\Exception\InvalidKey('params', $name, self::PARAMS);
            }
            $this->params[ $name ] = $value;
        }

        // Set missing parameters to their default values
        foreach (self::DEFAULTS as $name => $defaultValue) {
            if (!array_key_exists($name, $this->params)) {
                $this->params[ $name ] = $defaultValue;
            }
        }
    }

    public function toArray()
    {
        return $params;
    }

    public static function getDefaultConfiguration()
    {
        return new self(self::DEFAULTS);
    }
}
