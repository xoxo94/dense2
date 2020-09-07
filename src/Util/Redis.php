<?php
/**
 * Created by IntelliJ IDEA.
 * User: rochuan
 * Date: 2017/10/13
 * Time: 下午4:32
 */

namespace App\Util;
use Symfony\Component\Config\Definition\Exception\Exception;
class Redis{

    private $conn;

    /**
     *
     * @return Redis
     */
    private static $instance;

    /**
     *
     * @return Redis
     */
    public static function &instance()
    {
        if (!isset(self::$instance))
        {
            self::$instance = new Redis();
        }

        return self::$instance;
    }
    private function __construct(){

        global $kernel;

        if (! extension_loaded( 'redis' )) {
            throw new Exception('系统检测到redis扩展缺失或者redis扩展不存在请重新安装');
        }
        $config = $kernel->getContainer()->getParameter('app.redis');
        $this->conn = new \Redis();
        $this->conn->connect( $config['host'] , $config['port'] , $config['timeout'] );
        //$this->conn->pconnect($config['host'],$config['port']);
        //redis需要密码操作！
        if(!empty($config['password'])) {
            $this->conn->auth($config['password']);
        }

        $this->conn->select($config['db']);
    }

    /**
     * 删除key key可以是数组 也可以有多个参数
     *
     * @param string $key
     * @return boolen
     */
    public function delete( $key )
    {
        if (is_array( $key ))
        {
            return $this->conn->delete( $key );
        }

        $num = func_num_args(); // 获取函数参数个数


        if ($num > 1)
        {
            return $this->conn->delete( func_get_args() );
        }
        else
        {
            return $this->conn->delete( $key );
        }
    }

    /**
     * 清空所有数据（慎用）
     */
    public function flushAll()
    {
        return $this->conn->flushAll();
    }
    /**
     * 数据自增
     *
     * @param string $key
     * @param int $num
     * @return boolen
     */
    public function increment( $key , $num = 1 )
    {
        return $this->conn->incr( $key , $num );
    }

    /**
     * 数据自减
     *
     * @param string $key
     * @param int $num
     * @return boolen
     */
    public function decrement( $key , $num = 1 )
    {
        return $this->conn->decr( $key , $num );
    }

    /**
     * 判断key是否存在
     *
     * @param string $key
     * @return boolen
     */
    public function exists( $key )
    {
        return $this->conn->exists( $key );
    }

    /**
     * 设置过期时间
     *
     * @param string $key
     * @param int $time
     * @param boolen $flag
     * @return boolen
     */
    public function expire( $key , $time , $flag = false )
    {
        return $flag === false ? $this->conn->expire( $key , $time ) : $this->conn->expireAt( $key , $time );
    }

    /**
     * 返回名称为key的hash中所有的键（field）及其对应的value
     *
     * @param string $key
     * @return array
     */
    public function hGetAll( $key )
    {
        return $this->conn->hGetAll( $key );
    }

    /**
     * 从哈希队列中删除数据
     *
     * @param string $key
     * @param string $field
     * @return boolen
     */
    public function hDel( $key , $field )
    {
        return $this->conn->hDel( $key , $field );
    }

    /**
     * 向set类型的数组中存入数据
     *
     * @param string $key
     * @param string $value
     * @return boolen
     */
    public function sAdd( $key , $value )
    {
        $result = $this->conn->sAdd( $key , $value );
        return $result;
    }

    /**
     * 删除名称为key的set中的元素value
     *
     * @param string $key
     * @param string $value
     * * @return boolen
     */
    public function sRemove( $key , $value )
    {
        return $this->conn->sRem( $key , $value );
    }

    /**
     * 返回名称为key的set的所有元素
     *
     * @param string $key
     * @return array
     */
    public function sMembers( $key )
    {
        return $this->conn->sMembers( $key );
    }

    /**
     * 向名称为key的zset中添加元素member，score用于排序。
     *
     * @param string $key
     * @param int $score
     * @param string $member
     * @return boolen
     */
    public function zAdd( $key , $score , $member )
    {
        $result = $this->conn->zAdd( $key , $score , $member );
        return $result;
    }

    /**
     * 返回名称为key的zset（元素已按score从小到大排序）中的index从start到end的所有元素
     *
     * @param string $key
     * @param int $start
     * @param int $end
     * @param boolen $withscores
     * @return array
     */
    public function zRange( $key , $start , $end , $withscores = false )
    {
        return $this->conn->zRange( $key , $start , $end , $withscores );
    }

    /**
     * 返回名称为key的zset（元素已按score从大到小排序）中的index从start到end的所有元素
     *
     * @param string $key
     * @param int $start
     * @param int $end
     * @param boolen $withscores
     * @return array
     */
    public function zRevRange( $key , $start , $end , $withscores = false )
    {
        return $this->conn->zRevRange( $key , $start , $end , $withscores );
    }

    /**
     * 返回名称为key的zset中的index从start到end的所有元素
     *
     * @param string $key
     * @param int $start
     * @param int $end
     * @param boolen $withscores
     * @return array
     */
    public function zRangeByScore( $key , $start , $end , $withscores = false , $limit = array())
    {
        return $this->conn->zRangeByScore( $key , $start , $end , array('withscores' => $withscores, 'limit' => $limit) );
    }

    public function zRevRangeByScore( $key , $start , $end , $withscores = false , $limit = array())
    {
        return $this->conn->zReVRangeByScore( $key , $start , $end , array('withscores' => $withscores, 'limit' => $limit) );
    }

    /**
     * 删除名称为key的zset中的元素value
     *
     * @param string $key
     * @param string $member
     * @return boolen
     */
    public function zRemove( $key , $member )
    {
        return $this->conn->zRem( $key , $member );
    }

    /**
     * 返回名称为key的zset中元素member的score
     *
     * @param string $key
     * @param string $member
     * @return int
     */
    public function zScore( $key , $member )
    {
        return $this->conn->zScore( $key , $member );
    }

    /**
     * 删除score区间元素
     *
     * @param string $key
     * @param string $start
     * @param string $end
     *
     * @return int
     */
    public function zRemRangeByScore( $key, $start, $end )
    {
        return $this->conn->zRemRangeByScore( $key , $start, $end );
    }


    /**
     * 删除rank区间元素
     *
     * @param string $key
     * @param string $start
     * @param string $end
     *
     * @return int
     */
    public function zRemRangeByRank( $key, $start, $end, $withscores = FALSE )
    {
        return $this->conn->zRemRangeByRank( $key , $start, $end , array('withscores' => $withscores));
    }

    public function keys( $key )
    {
        return $this->conn->keys( $key );
    }

    public function zcard( $key )
    {
        return $this->conn->zcard( $key );
    }

    public function sinter( $key )
    {
        return $this->conn->sinter( $key );
    }

    public function select($db=0) {
        return $this->conn->select($db);
    }

    public function __clone(){
        throw new Exception('redis不允许被克隆');
    }

    /**
     * 设置值  构建一个字符串
     * @param string $key KEY名称
     * @param string $value  设置值
     * @param int $timeOut 时间  0表示无过期时间
     */
    public function set($key, $value, $timeOut=0) {
        if(is_array($value)){
            $value = json_encode($value);
        }
        $ret = $this->conn->set($key , $value);

        if($timeOut > 0){
            $this->conn->expire("{$key}",$timeOut);
        }
        return $ret;
    }

    /**
     * 获取值  构建一个字符串
     * @param string $key KEY名称
     */
    public function get($key){
        $ret = $this->conn->get($key);
        return $ret;
    }

    public function incr($key){
        $ret = $this->conn->incr($key);
        return $ret;
    }

    /**
     * 数据入哈希队列
     *
     * @param string $key
     * @param string $field
     * @param string $value
     * @return boolen
     */
    public function hset( $key , $field , $value )
    {
        if(is_array($value)){
            $value = json_encode($value);
        }
        $ret = $this->conn->hSet( $key , $field , $value );
        return $ret;
    }

    /**
     * 数据查哈希队列
     *
     * @param string $key
     * @param string $field
     * @param string $value
     * @return boolen
     */
    public function hget( $key , $field )
    {
        $ret = $this->conn->hGet( $key , $field );
        return $ret;
    }

    /**
     * 数据入队列
     *
     * @param string $key
     * @param string $value
     * @param boolen $right
     * @return boolen
     */
    public function push( $key , $value , $right = true )
    {
        if(is_array($value)){
            $value = json_encode($value);
        }
        return $right ? $this->conn->rPush( $key , $value ) : $this->conn->lPush( $key , $value );
    }

    /**
     * @desc 返回数据大小
     * @param null $key
     */
    public function lsize($key = null){
        $ret = $this->conn->lSize($key);
        return $ret ? $ret : 0;
    }

    /**
     * 数据出队列
     *
     * @param string $key
     * @param boolen $left
     * @return boolen
     */
    public function pop( $key , $left = true )
    {
        return $val = $left ? $this->conn->lPop( $key ) : $this->conn->rPop( $key );
    }

    /**
     * 关闭数据库连接
     *
     * @return object
     */
    public function close()
    {
        return $this->conn->close();
    }

    /**
     * 析构函数
     */
    public function __destruct()
    {
        $this->close();
    }


}