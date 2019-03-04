<?php
/**
 * Created by PhpStorm.
 * User: 南丞
 * Date: 2019/3/4
 * Time: 14:44
 *
 *
 *                      _ooOoo_
 *                     o8888888o
 *                     88" . "88
 *                     (| ^_^ |)
 *                     O\  =  /O
 *                  ____/`---'\____
 *                .'  \\|     |//  `.
 *               /  \\|||  :  |||//  \
 *              /  _||||| -:- |||||-  \
 *              |   | \\\  -  /// |   |
 *              | \_|  ''\---/''  |   |
 *              \  .-\__  `-`  ___/-. /
 *            ___`. .'  /--.--\  `. . ___
 *          ."" '<  `.___\_<|>_/___.'  >'"".
 *        | | :  `- \`.;`\ _ /`;.`/ - ` : | |
 *        \  \ `-.   \_ __\ /__ _/   .-` /  /
 *  ========`-.____`-.___\_____/___.-`____.-'========
 *                       `=---='
 *  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
 *           佛祖保佑       永无BUG     永不修改
 *
 */

namespace pf\cache;

use pf\config\Config;

class Cache
{
    protected $link;

    protected function driver($driver = null)
    {
        static $cache = [];
        $driver = $driver ?: Config::get('cache.driver');
        $driver = '\pf\cache\\build\\' . ucfirst($driver ?: 'file');
        if ($driver == 'file' || !isset($cache[$driver])) {
            $cache[$driver] = new $driver();
        }
        $this->link = $cache[$driver];
        $this->link->connect();
        return $this;
    }
    public function __call($method, $params)
    {
        if (is_null($this->link)) {
            $this->driver();
        }
        if (method_exists($this->link, $method)) {
            return call_user_func_array([$this->link, $method], $params);
        }
        return $this->link;
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([new static(), $name], $arguments);
    }
}

