<?php
/**
 * Created by PhpStorm.
 * User: Administrato
 * Date: 2018/6/19
 * Time: 19:25
 */
namespace App\Services;
use Illuminate\Support\Facades\DB;

class ReadExcelService
{
    public function ReadExcel($params)
    {
        $result = 0;
        DB::beginTransaction();
        foreach ($params as $key => $value)
        {
            switch ($key)
            {
                case 'employee':
                    $result +=$this->insertToEmployee($value);
                    break;
                case 'car':
                    $result +=$this->insertToCar($value);
                    break;
                case 'people_log':
                    $result +=$this->insertToPeopleLog($value);
                    break;
                case 'car_log':
                    $result +=$this->insertToCarLog($value);
                    break;
                case 'id':
                    $result +=$this->updateReport($value);
            }
        }
        if ($result =5)
        {
            DB::commit();
        }
        else
        {
            DB::rollBack();
        }
    }

    public function insertToPeopleLog($file_name)
    {
        $params = $this->loadFile($file_name);
        unset($params[0]);
        $insert = [];
        foreach ($params as $key => $value)
        {
            $value[0] = ($value[0]-70*365-19)*86400-8*3600;
            $insert[$key]['time'] = $value[0];
            $insert[$key]['card'] = $value[1];
            $insert[$key]['door'] = $value[3];
            $insert[$key]['name'] = $value[4];
            $insert[$key]['department'] = $value[7];
        }
        return DB::table('people_log')->insert($insert);
    }

    public function insertToCarLog($file_name)
    {
        $params = $this->loadFile($file_name);
        unset($params[0]);
        $insert = [];
        foreach ($params as $key => $value)
        {
            $employee = DB::table('car')->where('car_num', $value[0])->first();
            $value[7] = strtotime($value[7]);
            $value[3] = $value[3] == '入场'?'入口':'出口';
            $insert[$key]['time'] = $value[7];
            $insert[$key]['door'] = $value[3];
            $insert[$key]['name'] = $employee->name;
            $insert[$key]['department'] = $employee->department;
        }
        return DB::table('people_log')->insert($insert);
    }

    public function insertToEmployee($file_name)
    {
        $params = $this->loadFile($file_name);
        unset($params[0], $params[1]);
        $insert = [];
        foreach ($params as $key => $value)
        {
            $insert[$key]['name'] = $value[1];
            $insert[$key]['department'] = $value[2];
        }
      return DB::table('employee')->insert($insert);
    }

    public function insertToCar($file_name)
    {
        $params = $this->loadFile($file_name);
        unset($params[0]);
        $insert = [];
        foreach ($params as $key => $value)
        {
            $insert[$key]['car_num'] = $value[2];
            $insert[$key]['name'] = $value[3];
            $insert[$key]['department'] = $value[1];
        }
        return DB::table('car')->insert($insert);
    }

    public function loadFile($file_name)
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $reader = $reader->load(storage_path('data/'.$file_name));
        return $reader->getActiveSheet()->toArray();
    }

    public function updateReport($id)
    {
        return DB::table('reports')->where('id', $id)->update(['has_read'=>1]);
    }
}