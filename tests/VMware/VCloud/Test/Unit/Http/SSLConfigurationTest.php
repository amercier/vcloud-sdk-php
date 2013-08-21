<?php

namespace VMware\VCloud\Test\Unit\Http;

use VMware\VCloud\Test\ConfigurableTestCase;
use VMware\VCloud\Http\SSLConfiguration;

class SSLConfigurationTest extends ConfigurableTestCase
{
    public function testDefaultConfiguration()
    {
        $config = new SSLConfiguration();
        $this->assertEquals(SSLConfiguration::$DEFAULTS, $config->toArray());
    }

    public function testCustomConfiguration()
    {
        $config = new SSLConfiguration(
            array(
                SSLConfiguration::PARAM_VERIFY_PEER => true,
                SSLConfiguration::PARAM_VERIFY_HOST => true,
                SSLConfiguration::PARAM_CAFILE      => 'whatever.cer',
            )
        );
        $this->assertEquals(
            array(
                SSLConfiguration::PARAM_VERIFY_PEER => true,
                SSLConfiguration::PARAM_VERIFY_HOST => true,
                SSLConfiguration::PARAM_CAFILE      => 'whatever.cer',
            ),
            $config->toArray()
        );
    }

    /**
     * @expectedException \VMware\VCloud\Exception\InvalidArrayKey
     */
    public function testInvalidKey()
    {
        new SSLConfiguration(array('mer il et fou' => 'enkuler de rire'));
    }
}
