<?php

require_once dirname(__FILE__) . '/../../bootstrap.php';

use VMware\VCloud\Http\SSLConfiguration;

class SSLConfigurationTest extends VCloudTest
{
    public function testDefaultConfiguration()
    {
        $config = new SSLConfiguration();
        $this->assertEquals(SSLConfiguration::$DEFAULTS, $config->toArray());
    }

    public function testCustomConfiguration()
    {
        $config = new SSLConfiguration(array(
            SSLConfiguration::PARAM_VERIFY_PEER => true,
            SSLConfiguration::PARAM_VERIFY_HOST => true,
            SSLConfiguration::PARAM_CAFILE      => 'whatever.cer',
        ));
        $this->assertEquals(array(
            SSLConfiguration::PARAM_VERIFY_PEER => true,
            SSLConfiguration::PARAM_VERIFY_HOST => true,
            SSLConfiguration::PARAM_CAFILE      => 'whatever.cer',
        ), $config->toArray());
    }

    /**
     * @expectedException \VMware\VCloud\Exception\InvalidArrayKey
     */
    public function testInvalidKey()
    {
        new SSLConfiguration(array(
            'mer il et fou' => 'enkuler de rire'
        ));
    }
}
