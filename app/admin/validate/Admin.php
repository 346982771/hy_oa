<?php
namespace app\admin\validate;

use think\Validate;

class Admin extends Validate
{
    protected $rule = [
        ['username', 'require', '用户名不能为空'],
    ];
}