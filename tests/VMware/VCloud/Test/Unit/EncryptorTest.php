<?php

namespace VMware\VCloud\Test\Unit;

use VMware\VCloud\Test\ConfigurableTestCase;
use VMware\VCloud\Encryptor;

class EncryptorTest extends ConfigurableTestCase
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

    public function testWrongKey()
    {
        $encryptor = new Encryptor('qwertyuiop');
        $data = $this->config['cloudadmin']['password'];
        $key1 = '1234567890';
        $key2 = '0123456789';

        $encrytedData = $encryptor->encrypt($data, $key1);
        $decryptedData = $encryptor->decrypt($encrytedData, $key2);
        $this->assertEquals(false, $decryptedData, 'decrypt() should return false qhile the given key is wrong');

    }
}
