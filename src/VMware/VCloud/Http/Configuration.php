<?php

namespace VMware\VCloud\Http;

class Configuration
{
    protected $proxyConfiguration;
    protected $sslConfiguration;

    public function __construct(
        ProxyConfiguration $proxyConfiguration = null,
        SSLConfiguration $sslConfiguration = null
    ) {
        $this->proxyConfiguration = $proxyConfiguration === null
        ? ProxyConfiguration::getDefaultConfiguration()
        : $proxyConfiguration;
        $this->sslConfiguration = $sslConfiguration === null
        ? SSLConfiguration::getDefaultConfiguration()
        : $sslConfiguration;
    }

    public function toArray()
    {
        return array_merge(
            $this->proxyConfiguration->toArray(),
            $this->sslConfiguration->toArray()
        );
    }

    public static function getDefaultConfiguration()
    {
        return new self();
    }
}
