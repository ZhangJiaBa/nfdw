<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/25
 * Time: 11:46
 */
namespace App\Admin\Extensions;

use App\Models\Contract\Contract;
use App\Models\Contract\ContractItems;
use App\Models\Department\Department;
use App\Models\Project\Project;
use App\Models\Reports\ReportFomat;
use App\Models\Reports\ReportType;
use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;

class ConstractExcelExpoter extends AbstractExporter
{
    public function export()
    {
        Excel::create('合同导出', function($excel) {

            $excel->sheet('合同列表', function($sheet) {
                $sheet->setWidth(array(
                    'A'     =>  5,
                    'B'     =>  20,
                    'C'     =>  20,
                    'D'     =>  20,
                    'E'     =>  20,
                    'F'     =>  20,
                    'G'     =>  20,
                    'H'     =>  20,
                    'I'     =>  20,
                ));
                // 这段逻辑是从表格数据中取出需要导出的字段
                $rows = collect($this->getData())->map(function ($item) {
                    $data['id'] = $item['id'];
                    $data['contract_num'] = $item['contract_num'];
                    $data['project_name'] = $item['project_name'];
                    $data['party_a'] = $item['party_a'];
                    if ($item['contract_type'] == Contract::TYPE_WAI){
                        $data['contract_type'] = "委托";
                    }else {
                        $data['contract_type'] = "中标";
                    }

                    $data['sign_date'] = $item['sign_date'];
                    $data['contract_amount'] = $item['contract_amount'];
                    $data['limit_time'] = $item['limit_time'];
                    $data['dp_id'] = Department::where('id',$item['dp_id'])->value('name');
                    return array_only($data, ['id','contract_num','project_name','party_a','contract_type','sign_date','contract_amount','limit_time','dp_id']);
                });
                $sheet->row(1, array(
                    '序号','合同编号','工程名称','甲方名称','合同类型','签订日期','合同金额','合同工期','负责部门'
                ));
                $sheet->rows($rows);

            });

        })->export('xls');
    }
}