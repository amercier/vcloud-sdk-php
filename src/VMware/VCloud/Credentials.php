<?php
namespace VMware\VCloud;

/**
 * Data object that contains credentials information:
 *   - organization
 *   - username
 *   - password
 *
 * The password is stored encrypted using AES encryption with Rijndael 128-bit
 * cypher. The key used is randomly generated.
 */
class Credentials extends AbstractObject
{

    protected static const PASSWORD_MCRYPT_KEY = strtr(base64_encode(openssl_random_pseudo_bytes(1024)), '+', '.');
    public static const PASSWORD_MCRYPT_CIPHER = MCRYPT_RIJNDAEL_128;
    public static const PASSWORD_MCRYPT_MODE   = MCRYPT_MODE_CFB;

    /**
     * The organization
     * @var string
     */
    protected $organization = null;

    /**
     * The username
     * @var string
     */
    protected $username = null;

    /**
     * The password, encrypted using mcrypt module
     * @var string
     */
    protected $password = null;

    /**
     * Create a new Credentials object using a key-value array containing:
     *   - organization: The organization
     *   - username    : The username
     *   - password    : The password
     *
     * @param array $params A key-value array containing the credetials
     */
    public function __construct($params)
    {
        foreach (array('organization', 'username', 'password') as $parameter) {
            if (!isset($params[$parameter])) {
                throw new VMware\VCloud\Exception\MissingParameter($parameter);
            }
        }

        $this->set('organization', $params['organization']);
        $this->set('username', $params['username']);
        $this->setPassword($params['password']);
    }

    public function getOrganization()
    {
        return $this->get('organization');
    }

    public function getUsername()
    {
        return $this->get('username');
    }

    public function getPassword()
    {
        return mcrypt_decrypt(
            self::PASSWORD_MCRYPT_CIPHER,
            self::PASSWORD_MCRYPT_KEY,
            $this->get('password'),
            self::PASSWORD_MCRYPT_MODE
        );
    }

    public function setPassword($password)
    {
        $this->set(
            'password',
            mcrypt_encrypt(
                self::PASSWORD_MCRYPT_CIPHER,
                self::PASSWORD_MCRYPT_KEY,
                $password,
                self::PASSWORD_MCRYPT_MODE
            )
        );
    }

    public function toArray()
    {
        return array(
            'username' => $credentials->getUsername() . '@' . $credentials->getOrganization(),
            'password' => $credentials->getPassword(),
        );
    }
}
