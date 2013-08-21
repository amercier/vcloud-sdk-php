<?php

namespace VMware\VCloud\Test\Unit\Http;

use VMware\VCloud\Test\ConfigurableTestCase;
use VMware\VCloud\Http\ProxyConfiguration;

class ProxyConfigurationTest extends ConfigurableTestCase
{
    public function testDefaultConfiguration()
    {
        $config = new ProxyConfiguration();
        $this->assertEquals(ProxyConfiguration::$DEFAULTS, $config->toArray());
    }

    public function testCustomConfiguration()
    {
        $config = new ProxyConfiguration(
            array(
                ProxyConfiguration::PARAM_HOST     => 'myproxy',
                ProxyConfiguration::PARAM_PORT     => 3128,
                ProxyConfiguration::PARAM_USER     => 'user',
                ProxyConfiguration::PARAM_PASSWORD => 'pass',
            )
        );
        $this->assertEquals(
            array(
                ProxyConfiguration::PARAM_HOST     => 'myproxy',
                ProxyConfiguration::PARAM_PORT     => 3128,
                ProxyConfiguration::PARAM_USER     => 'user',
                ProxyConfiguration::PARAM_PASSWORD => 'pass',
            ),
            $config->toArray()
        );
    }

    /**
     * @expectedException \VMware\VCloud\Exception\InvalidArrayKey
     */
    public function testInvalidKey()
    {
        new ProxyConfiguration(array('mer il et fou' => 'enkuler de rire'));
    }
}
