<?php

namespace fpasquer\SimpleRecaptchaBundle\src\TwigExtensions;

use Exception;
use fpasquer\SimpleRecaptchaBundle\src\Services\GenerateKey;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SimpleRecaptchaTwig extends AbstractExtension
{
    /**
     * @var GenerateKey
    */
    private $keyGenerator;

    /**
     * @var string
     */
    private $pathImage = __DIR__ . "/../../../../public/Images/SimpleRecaptcha/";

    /**
     * @var string
    */
    private $image = null;

//    /**
//     * @param SessionInterface $session
//     * @throws \Exception
//    */
//    public function __construct(SessionInterface $session)
//    {
//        $this->keyGenerator = new GenerateKey($session);
//        if (file_exists($this->pathImage) === false || is_dir($this->pathImage) === false) {
//            if (mkdir($this->pathImage, 0777, true) === false) {
//                throw new Exception("Not possible to create ImagesFolder");
//            }
//        }
//    }

    public function getFunctions()
    {
        return [new TwigFunction('simpleRecaptchaImage', [$this, 'simpleRecaptchaImage'])];
    }

    /**
     * Return the path of the TMP Image
     * @param SessionInterface $session
     * @return string
     * @throws Exception
     */
    public function simpleRecaptchaImage(SessionInterface $session) : string
    {
        $this->keyGenerator = new GenerateKey($session);
        if (file_exists($this->pathImage) === false || is_dir($this->pathImage) === false) {
            if (mkdir($this->pathImage, 0777, true) === false) {
                throw new Exception("Not possible to create ImagesFolder");
            }
        }

        $this->image = uniqid($this->pathImage) . ".png";
        $key = $this->keyGenerator->run();
        $image = imagecreate(200, 50);
        imagecolorallocate($image, 255, 128, 0);
        $white = imagecolorallocate($image, 255, 255, 255);
        imagestring($image, 5, 0, 0, $key, $white);
        imagepng($image, $this->image);
        imagedestroy($image);
        return str_replace($this->pathImage, "Images/SimpleRecaptcha/", $this->image);
    }
}