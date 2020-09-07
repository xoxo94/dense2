<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/21 0021
 * Time: 下午 13:50
 */
// src/Twig/AppExtension.php
namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
//    public function getFilters()
//    {
//        return [
//            new TwigFilter('ctrans', [TranslatorService::instance(), 'ctrans']),
//        ];
//    }
//
//    public function getFunctions()
//    {
//        return [
//            new TwigFunction('getW', [WebConfigInitService::instance(), 'getW']),
//        ];
//    }
}