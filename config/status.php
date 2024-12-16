<?php
// +----------------------------------------------------------------------
// | 业务层状态码
// +----------------------------------------------------------------------

return [
    'success' => 1,
    'error' => 0,
    'not_login' => -1,
    'user_is_register' => -2,
    'action_not_found' => -3,
    'controller_not_found' => -4,

    'mysql' => [
	    'table_normal' => 1,//正常
	    'table_pedding' => 0,//待审
    ],
];