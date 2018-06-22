<?php

namespace Tests\Unit;

use App\Http\Controllers\Backend\Index\IndexController;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CountTest extends TestCase
{
    public function dateProvider()
    {
        return [['time_list' => $this->getTimeList(), 'id'=> 1]];
    }

    public function getTimeList()
    {
            $began_time = strtotime('2018-06-01') + 8.5 * 60 * 60;
            $end_time = strtotime('2018-06-30') + 8.5 * 60 * 60;
            for ($began_time; $began_time <= $end_time; $began_time += 60 * 60 * 24) {
                $time['time_range'][] = intval($began_time);
                $time['time_range'][] = intval($began_time + 9 * 60 * 60);
            }
            return $time;
    }

    /**
     * @dataProvider dateProvider
     */
    public function testGetDate($expect_time,  $id)
    {
        $c = new IndexController();
        $actual_times = $c->getTime($id);
        $this->assertSame($expect_time, $actual_times);
    }
}
