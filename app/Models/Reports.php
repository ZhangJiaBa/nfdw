<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Traits\AdminBuilder;

class Reports extends Model
{
    use AdminBuilder;
    protected $table = 'reports';
    protected $guarded = [];
}
