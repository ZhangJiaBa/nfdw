<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [


    'system' => env('SYSTEM', '检验检测机构信息管理系统'),

    'company' => env('COMPANY', '广东盛翔交通工程检测有限公司'),

    'stamp' => env('STAMP', '511400'),

    'telephone' => env('TEL', '020-38273450'),

    'address' => env('ADDR', '广州市番禺区东环街番禺大道北1161号'),

    'tax' => env('TAX', '020-38273450'),

    'e-mail' => env('E-MAIL', 'gdsxiang2015@163.com'),

    'powered' => env('POWERED', '©广东盛翔版权所有'),

    'version' => env('VERSION', app()->version()),

    'server' => env('SER', array_get($_SERVER, 'SERVER_SOFTWARE')),

    'url' => env('URL', 'http://121.8.210.226:8080/lims/')

];
