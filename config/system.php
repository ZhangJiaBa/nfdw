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


    'system' => env('SYSTEM', '南方电网考勤管理系统'),

    'company' => env('COMPANY', '南方电网'),

    'stamp' => env('STAMP', '511400'),

    'telephone' => env('TEL', ''),

    'address' => env('ADDR', '广州市越秀区'),

    'tax' => env('TAX', ''),

    'e-mail' => env('E-MAIL', ''),

    'powered' => env('POWERED', '©南方电网'),

    'version' => env('VERSION', app()->version()),

    'server' => env('SER', array_get($_SERVER, 'SERVER_SOFTWARE')),

    'url' => env('URL', '')

];
