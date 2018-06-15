<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/25
 * Time: 11:46
 */
namespace App\Admin\Extensions;

use App\Models\Project\Project;
use App\Models\Reports\ReportFomat;
use App\Models\Reports\ReportType;
use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;

class ReportExcelExpoter extends AbstractExporter
{
    public function export()
    {
        Excel::create('报告导出', function($excel) {

            $excel->sheet('报告列表', function($sheet) {
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
                    'J'     =>  20,
                    'K'     =>  20,
                ));
                // 这段逻辑是从表格数据中取出需要导出的字段
                $rows = collect($this->getData())->map(function ($item) {
                    $project_id = $item['pj_id'];
                    $project = Project::where('id',$project_id)->first();
                    $data['id'] = $item['id'];
                    $data['entrustment_number'] = $project->entrustment_number;
                    $data['entrustment_unit'] = $project->entrustment_unit;
                    $data['project_name'] = $project->name;
                    $data['rp_number'] = $item['rp_number'];
                    $data['rp_type'] = $item['rp_type'];
                    $data['rp_format'] = $item['rp_format'];
                    $data['sample'] = $item['sample'];
                    $data['maker'] = $item['maker'];
                    $data['report_name'] = $item['report_name'];
                    $data['created_at'] = $item['created_at'];
                    return array_only($data, ['id','entrustment_number','entrustment_unit','project_name','rp_number', 'rp_type', 'rp_format','sample','maker','report_name','created_at']);
                });
                $sheet->row(1, array(
                    '序号','委托编号','委托单位','项目名称','报告编号','报告类型','报告格式','样品/记录编号','上传者','报告提名','上传时间'
                ));
                $sheet->rows($rows);

            });

        })->export('xls');
    }
}