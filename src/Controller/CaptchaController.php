<?php

namespace App\Controller;

use Gregwar\Captcha\PhraseBuilder;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Gregwar\Captcha\CaptchaBuilder;
use Symfony\Component\HttpFoundation\Response;
class CaptchaController
{
    /**
     * @Route("/get/Captcha", name="get_captcha")
     */
    public function indexAction(Request $request)
    {

        $width = intval($request->get('width')) ?: 360;  // 验证码图片的宽度
        $height = intval($request->get('height')) ?: 90;  // 验证码图片的高度
        $length = 5;  // 验证码字符的长度
        $charset='0123456789';
        $no_effect = false;  // 是否忽略验证图片上的干扰线条


        $pharse= new PhraseBuilder();
        $captcha = new CaptchaBuilder($pharse->build($length,$charset));
        $image = $captcha->setIgnoreAllEffects($no_effect)->build($width, $height)->get();

        $session = $request->getSession();
        $session->set('captcha', $captcha->getPhrase());

        $res = new Response($image);
        $res->headers->set('content-type', 'image/jpeg');

        return $res;
    }
    /**
     * @Route("/captcha/code", name="window")
     */
    public function windowAction(Request $request)
    {

        $width = intval($request->get('width')) ?: 119;  // 验证码图片的宽度
        $height = intval($request->get('height')) ?: 54;  // 验证码图片的高度
        $length = 4;  // 验证码字符的长度
        $charset='0123456789';
        $no_effect = false;  // 是否忽略验证图片上的干扰线条

        $pharse = new PhraseBuilder();
        $captcha = new CaptchaBuilder($pharse->build($length,$charset));
        $image = $captcha->setIgnoreAllEffects($no_effect)->build($width, $height)->get();

        $session = $request->getSession();
        $session->set('code', $captcha->getPhrase());

        $res = new Response($image);
        $res->headers->set('content-type', 'image/jpeg');

        return $res;

    }
}
