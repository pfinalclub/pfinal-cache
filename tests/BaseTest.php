<?php

/**
 * Created by PhpStorm.
 * User: 南丞
 * Date: 2019/3/4
 * Time: 14:54
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

use pf\cache\Cache;
use pf\config\Config;

class BaseTest extends \PHPUnit\Framework\TestCase
{
    public function setUp()
    {
        parent::setUp();
        Config::loadFiles('config');
    }

    public function testBase()
    {
        Cache::set('a', 1);
        Cache::set('b', 1);
        $this->assertEquals(Cache::get('a'), 1);
        Cache::del('a');
        $this->assertNull(Cache::get('a'));
        $this->assertTrue(Cache::flush());
    }
}