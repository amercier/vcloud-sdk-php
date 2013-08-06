<?php

namespace VMware\VCloud\Http;

class Configuration
{
    protected $proxyConfiguration;
    protected $sslConfiguration;

    public function toArray()
    {
        return array_merge(
            $this->proxyConfiguration->toArray(),
            $this->sslConfiguration->toArray()
        );
    }

    public static function getDefaultConfiguration()
    {
        return new self(
            ProxyConfiguration::getDefaultConfiguration(),
            SSLConfiguration::getDefaultConfiguration()
        );
    }
}
