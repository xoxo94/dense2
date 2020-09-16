<?php
/**
 * Created by PhpStorm.
 * User: VcodeLam
 * Date: 2019/6/1 0001
 * Time: 16:38
 */
namespace App\Controller;

use App\Util\Aes;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends BaseController
{
    /**
     * @Route("/", name="home")
     */
    public function homeAction(Request $request)
    {
        $session = ($request->getSession());
        if ($session->get('loginuser')) {
            return $this->render('Default/dense.html.twig');
        }
        return $this->render('Default/index.html.twig');
    }
    /**
     * @Route("/index", name="index")
     */
    public function indexAction(Request $request)
    {

        var_dump($request->getClientIp().'====>'.$this->getIp());

        file_put_contents('../var/logs/ip.log','['.date('Y-m-d H:i:s').']'.$this->getIp().PHP_EOL,FILE_APPEND);

        file_put_contents('../var/logs/ip.log','['.date('Y-m-d H:i:s').']'.$request->getClientIp().PHP_EOL,FILE_APPEND);


        $session = ($request->getSession());
        if ($session->get('loginuser')) {
            return $this->render('Default/dense.html.twig');
        }

        return $this->render('Default/index.html.twig');
    }
    /**
     * @Route("/user/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $user=trim($request->get('username'));
        $pass=trim($request->get('pass'));
        if(empty($user) || empty($pass)){return $this->ErrorMsg('密码/用户名不能空');}
        if($user=='lhx' && $pass=='lin1401293011'){
            $session = ($request->getSession());
            $session->set('loginuser',['username'=>$user,'id'=>1]);
            $session->set('SESSION_ID_SIGN',md5(session_id().md5(date('Ymd')).$request->getClientIp()));
            $session->set('loginTime',time());
            $session->save();
            return $this->Success('/dense');
        }
        return $this->ErrorMsg('密码/用户名错误');
    }
    /**
     * @Route("/dense", name="dense")
     */
    public function denseAction(Request $request)
    {
        return $this->render('Default/dense.html.twig');
    }
    /**
     * @Route("/user/txt", name="txt")
     */
    public function txtAction(Request $request)
    {
        $txt=trim($request->get('txt'));
        if(empty($txt) ){exit(11);}
        $textArray=explode(';',$txt);
        if (!is_array($textArray)) {return $this->ErrorMsg('狗笔');}
        $base=Aes::instance()->encrypt(json_encode($textArray));
        return $this->Success($base);
    }

    /**
     * @Route("/user/see", name="see")
     */
    public function seeAction(Request $request)
    {
        $txt=trim($request->get('txt'));
        if(empty($txt) ){exit(2222);}
        $jsonbase=Aes::instance()->decrypt($txt);
        $array=json_decode($jsonbase,true);
        if (!is_array($array)) {return $this->ErrorMsg('妈的滚');}

        return $this->Success($array);
    }





}