<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

namespace think\api\auth;

use think\api\Auth;

class CompositeAuth extends Auth
{

    protected $config = [
        'auth_methods' => [
            "think\\api\\auth\\HttpBearerAuth",
            "think\\api\\auth\\QueryParamAuth"
        ]
    ];

    public function authenticate()
    {
        foreach ($this->config['auth_methods'] as $i => $auth) {
            if (!$auth instanceof Auth) {
                $this->config['auth_methods'][$i] = $auth = new $auth;
                if (!$auth instanceof Auth) {
                    throw new \InvalidArgumentException(get_class($auth) . ' must extends think\\api\\Auth');
                }
            }
            $identity = $auth->authenticate();
            if ($identity !== null) {
                return $identity;
            }
        }
    }
}