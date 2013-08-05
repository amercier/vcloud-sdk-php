<?php

namespace VMware\VCloud;

class Encryptor
{
    protected $salt;

    const MCRYPT_CIPHER = 'MCRYPT_RIJNDAEL_128';
    const MCRYPT_MODE   = 'MCRYPT_MODE_CFB';

    public function __construct($salt = null)
    {
        if (!defined(self::MCRYPT_CIPHER)) {
            throw new Exception\MissingPHPModule('mcrypt', 'Constant ' . self::MCRYPT_CIPHER . ' is missing');
        }
        if (!defined(self::MCRYPT_MODE)) {
            throw new Exception\MissingPHPModule('mcrypt', 'Constant ' . self::MCRYPT_MODE . ' is missing');
        }

        $this->salt = $salt === null ? self::generateRandomSalt() : $salt;
    }

    public function encrypt($decrypted, $key)
    {
        // Build a 256-bit $key which is a SHA256 hash of $salt and $key.
        $key = hash('SHA256', $this->salt . $key, true);

        // Build $iv and $iv_base64.  We use a block size of 128 bits (AES
        // compliant) and CBC mode.  (Note: ECB mode is inadequate as IV is not used.)
        srand();
        $iv = mcrypt_create_iv(
            mcrypt_get_iv_size(
                constant(self::MCRYPT_CIPHER),
                constant(self::MCRYPT_MODE)
            ),
            MCRYPT_RAND
        );
        if (strlen($iv_base64 = rtrim(base64_encode($iv), '=')) != 22) {
            return false;
        }

        // Encrypt $decrypted and an MD5 of $decrypted using $key.  MD5 is fine
        // to use here because it's just to verify successful decryption.
        $encrypted = base64_encode(
            mcrypt_encrypt(
                constant(self::MCRYPT_CIPHER),
                $key,
                $decrypted . md5($decrypted),
                constant(self::MCRYPT_MODE),
                $iv
            )
        );

        // We're done!
        return $iv_base64 . $encrypted;
    }

    public function decrypt($encrypted, $key)
    {
        // Build a 256-bit $key which is a SHA256 hash of $this->salt and $key.
        $key = hash('SHA256', $this->salt . $key, true);

        // Retrieve $iv which is the first 22 characters plus ==, base64_decoded.
        $iv = base64_decode(substr($encrypted, 0, 22) . '==');

        // Remove $iv from $encrypted.
        $encrypted = substr($encrypted, 22);

        // Decrypt the data.  rtrim won't corrupt the data because the last 32
        // characters are the md5 hash; thus any \0 character has to be padding.
        $decrypted = rtrim(
            mcrypt_decrypt(
                constant(self::MCRYPT_CIPHER),
                $key,
                base64_decode($encrypted),
                constant(self::MCRYPT_MODE),
                $iv
            ),
            "\0\4"
        );

        // Retrieve $hash which is the last 32 characters of $decrypted.
        $hash = substr($decrypted, -32);

        // Remove the last 32 characters from $decrypted.
        $decrypted = substr($decrypted, 0, -32);

        // Integrity check.  If this fails, either the data is corrupted, or the password/salt was incorrect.
        if (md5($decrypted) != $hash) {
            return false;
        }

        // Yay!
        return $decrypted;
    }

    public static function generateRandomSalt()
    {
        return strtr(base64_encode(openssl_random_pseudo_bytes(128)), '+', '.');
    }
}
