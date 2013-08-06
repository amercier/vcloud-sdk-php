<?php

require_once dirname(__FILE__) . '/../bootstrap.php';

use VMware\VCloud\Encryptor;

class EncryptorTest extends VCloudTest
{
    public function testDecryptWithDefaultSalt()
    {
        $encryptor = new Encryptor();
        $data = $this->config['cloudadmin']['password'];
        $key = '1234567890';

        $encrytedData = $encryptor->encrypt($data, $key);
        $this->assertNotEquals($data, $encrytedData, 'Encrypted data should be different than the original one');

        $decryptedData = $encryptor->decrypt($encrytedData, $key);
        $this->assertEquals($data, $decryptedData, 'Decrypted data should be equivalent to the original one');
    }

    public function testDecryptWithGivenSalt()
    {
        $encryptor = new Encryptor('qwertyuiop');
        $data = $this->config['cloudadmin']['password'];
        $key = '1234567890';

        $encrytedData = $encryptor->encrypt($data, $key);
        $this->assertNotEquals($data, $encrytedData, 'Encrypted data should be different than the original one');

        $decryptedData = $encryptor->decrypt($encrytedData, $key);
        $this->assertEquals($data, $decryptedData, 'Decrypted data should be equivalent to the original one');
    }
}
