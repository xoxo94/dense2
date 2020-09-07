<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/15 0015
 * Time: 下午 15:47
 */

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RequestListener
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        global $loginuser; global $kernel;
        $request = $event->getRequest();
        $session_loginuser= $request->getSession()->get('loginuser');
        $loginuser=$session_loginuser;

        if(! $this->checkURIIsLogin($request->getPathInfo())  ) {
            $session_id_sign=$request->getSession()->get('SESSION_ID_SIGN');
            $session_chk_sign=md5(session_id().md5(date('Ymd')).$request->getClientIp());
            $loginTime=$request->getSession()->get('loginTime');
            $time=time();
            if (empty($session_loginuser) ||  $session_chk_sign !=$session_id_sign || ($loginTime-$time)>(2*60*60)){
                $request->getSession()->remove('loginuser');
                $request->getSession()->remove('SESSION_ID_SIGN');
                $request->getSession()->remove('loginTime');
                $request->getSession()->save();
                header('Location: /index');exit;
            }
        }
    }

    private function checkURIIsLogin($uri){
        $notrights=['/index','/user/login'];
        if(substr($uri,0,6)=='/_wdt/' || substr($uri,0,4)=='/js/'|| substr($uri,0,5)=='/css/' || substr($uri,0,17)=='/uploader/images/' || substr($uri,0,19)=='/uploader/document/' || substr($uri,0,18)=='/payment/callback/' ){return true;}

        if(in_array($uri,$notrights)){
            return true;
        }
        return false;
    }
}