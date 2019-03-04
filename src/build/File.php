<?php
/**
 * Created by PhpStorm.
 * User: 南丞
 * Date: 2019/3/4
 * Time: 14:58
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

namespace pf\cache\build;

use Exception;
use pf\config\Config;
use pf\diropt\Diropt;

class File implements InterfaceCache
{
    private $dir;

    public function connect()
    {
        $this->dir(Config::get('cache.file.dir'));
    }

    protected function dir($dir)
    {
        $this->dir = $dir;
        if (!Diropt::create($this->dir)) {
            throw new Exception("缓存目录创建失败或目录不可写");
        }
        return $this;
    }

    private function getFile($name)
    {
        return $this->dir . '/' . md5($name) . ".php";
    }

    public function set($name, $data, $expire = 3600)
    {
        $file = $this->getFile($name);
        //缓存时间
        $expire = sprintf("%010d", $expire);
        $data = $expire . serialize($data);
        return file_put_contents($file, $data);
    }

    public function get($name)
    {
        $file = $this->getFile($name);
        if (!is_file($file) || !is_readable($file)) {
            return null;
        }
        $content = file_get_contents($file);
        $expire = intval(substr($content, 0, 10));
        //修改时间
        $mtime = filemtime($file);
        //缓存失效处理
        if ($expire > 0 && $mtime + $expire < time()) {
            if (is_file($file)) {
                unlink($file);
            }
            return false;
        }
        return unserialize(substr($content, 10));
    }

    public function del($name)
    {
        $file = $this->getFile($name);
        return Diropt::delFile($file);
    }

    public function flush()
    {
        return Diropt::del($this->dir);
    }

}