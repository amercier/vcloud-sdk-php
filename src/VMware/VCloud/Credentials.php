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

    protected static $mcryptKey = null;

    const PASSWORD_MCRYPT_CIPHER = 'MCRYPT_RIJNDAEL_128';
    const PASSWORD_MCRYPT_MODE   = 'MCRYPT_MODE_CFB';

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
     * The password encryptor
     * @var Encryptor
     */
    protected $encryptor = null;

    /**
     * The password key
     * @var string
     */
    protected $passwordKey = null;

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
    public function __construct($organization, $username = null, $password = null)
    {
        // Add the possibility to invoke __construct(array(organization => ..., username => ..., password => ...))
        if (is_array($organization) && $username === null && $password === null) {
            foreach (array('organization', 'username', 'password') as $parameter) {
                if (!array_key_exists($parameter, $organization)) {
                    throw new Exception\MissingParameter($parameter . ' in $params');
                }
            }
            $username = $organization['username'];
            $password = $organization['password'];
            $organization = $organization['organization'];
        }

        $this->set('organization', $organization);
        $this->set('username', $username);
        $this->set('encryptor', new Encryptor());
        $this->set('passwordKey', Encryptor::generateRandomSalt());
        $this->setPassword($password);
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
        return $this->encryptor->decrypt($this->password, $this->passwordKey);
    }

    public function setPassword($password)
    {
        return $this->set('password', $this->encryptor->encrypt($password, $this->passwordKey));
    }

    public function toArray()
    {
        return array(
            'username' => $credentials->getUsername() . '@' . $credentials->getOrganization(),
            'password' => $credentials->getPassword(),
        );
    }

    public static function getMcryptKey()
    {
        if (self::$mcryptKey === null) {
            self::$mcryptKey = strtr(base64_encode(openssl_random_pseudo_bytes(16)), '+', '.');
        }
        return self::$mcryptKey;
    }
}
