<?php

require_once dirname(__FILE__) . '/../../bootstrap.php';

use VMware\VCloud\Http\Configuration;
use VMware\VCloud\Http\ProxyConfiguration;
use VMware\VCloud\Http\SSLConfiguration;

class ConfigurationTest extends VCloudTest
{
    public function testDefaultConfiguration()
    {
        $config = new Configuration();
        $this->assertArrayContains(ProxyConfiguration::$DEFAULTS, $config->toArray());
        $this->assertArrayContains(SSLConfiguration::$DEFAULTS, $config->toArray());
        $this->assertEquals(Configuration::getDefaultConfiguration(), $config);
    }

    public function testCustomConfiguration()
    {
        $config = new Configuration(
            new ProxyConfiguration(array(
                ProxyConfiguration::PARAM_HOST     => 'myproxy',
                ProxyConfiguration::PARAM_PORT     => 3128,
                ProxyConfiguration::PARAM_USER     => 'user',
                ProxyConfiguration::PARAM_PASSWORD => 'pass',
            )),
            new SSLConfiguration(array(
                SSLConfiguration::PARAM_VERIFY_PEER => true,
                SSLConfiguration::PARAM_VERIFY_HOST => true,
                SSLConfiguration::PARAM_CAFILE      => 'whatever.cer',
            ))
        );

        $this->assertArrayContains(array(
            ProxyConfiguration::PARAM_HOST     => 'myproxy',
            ProxyConfiguration::PARAM_PORT     => 3128,
            ProxyConfiguration::PARAM_USER     => 'user',
            ProxyConfiguration::PARAM_PASSWORD => 'pass',
        ), $config->toArray());

        $this->assertArrayContains(array(
            SSLConfiguration::PARAM_VERIFY_PEER => true,
            SSLConfiguration::PARAM_VERIFY_HOST => true,
            SSLConfiguration::PARAM_CAFILE      => 'whatever.cer',
        ), $config->toArray());
    }
}
