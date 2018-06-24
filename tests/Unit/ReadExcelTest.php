<?php

namespace Tests\Unit;

use App\Http\Controllers\Backend\Index\IndexController;
use App\Services\ReadExcelService;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReadExcelTest extends TestCase
{
    public function dateProvider()
    {
        return [['table' => 'employee', 'insert'=>['name' => 'test_name', 'department' => 'test_department']],
            ['table' => 'car', 'insert' => ['name' => 'test_name', 'department' => 'test_department', 'car_num' => 'test_num']],
            ['table' => 'people_log', 'insert' => ['name' => 'test_name', 'department' => 'test_department',
                'time' => strtotime('2018-06-11 9:15:42'), 'door' => '入口']]];
    }

    /**
     * @dataProvider dateProvider
     */
   public function testInsert($table,  $insert)
    {
        $c = new ReadExcelService();
        $actual = $c->insert($table, $insert);
        DB::table($table)->where('name', 'test_name')->delete();
        $this->assertTrue($actual);
    }
}
