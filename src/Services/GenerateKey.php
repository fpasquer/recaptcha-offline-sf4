<?php

namespace fpasquer\SimpleRecaptchaBundle\src\Services;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GenerateKey
{
    /**
     * @var int
    */
    private $length;

    /**
     * @var string
    */
    private $pathImage = __DIR__ . "/../../../../public/Images/";

    /**
     * @var string
    */
    private $sessionKey = "HleL5ZaENrLRFfvq";

    /**
     * @var string
     */
    private $sessionKeyPrev = "oKdjIUQTkJzCHNIba2rr";

    /**
     * @var SessionInterface
    */
    private $session;

    /**
     * @var bool
    */
    private $valid = false;

    /**
     * @param SessionInterface $session
     * @param int $length
     * @throws Exception
    */
    public function __construct(SessionInterface $session, int $length = 5)
    {
        $this->length = $length;
        $this->session = $session;
    }

    /**
     * @return null | string
     * @throws Exception
    */
    public function run()
    {
        if (($keyPrev = $this->session->get($this->sessionKey)) !== null) {
            $this->session->set($this->sessionKeyPrev, $keyPrev);
        }
        $key = substr(md5((random_bytes($this->length))), 0, $this->length);
        $this->session->set($this->sessionKey, $key);
        return $this->getKey();
    }

    /**
     * @return null | string
    */
    public function getKey() : ?string
    {
        return $this->session->get($this->sessionKeyPrev);
    }

    public function handleRequest(Request $request)
    {
        $value = $request->get('SimpleRecaptcha');
        $this->valid = $value === $this->getKey();
        dump($value, $this->getKey());
    }

    public function isValid() : bool
    {
        return $this->valid;
    }
}