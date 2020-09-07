<?php
/**
 * Created by PhpStorm.
 * User: VcodeLam
 * Date: 2019/6/1 0001
 * Time: 16:38
 */
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


class BaseController extends AbstractController
{


    public function ErrorMsg($msg)
    {
        return new JsonResponse(['code'=>false,'msg'=>$msg]);
    }
    public function Success($data)
    {
        return new JsonResponse(['code'=>true,'msg'=>'','data'=>$data]);
    }


}