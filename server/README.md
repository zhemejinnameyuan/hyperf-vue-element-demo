# hyperf-demo

#### 环境要求
* PHP >= 7.2
* Swoole PHP 扩展 >= 4.5，并关闭了 Short Name
* OpenSSL PHP 扩展
* JSON PHP 扩展
* PDO PHP 扩展 （如需要使用到 MySQL 客户端）
* Redis PHP 扩展 （如需要使用到 Redis 客户端） 
 

#### app目录说明
```
 hyperf 项目目录
 ├─app  应用目录
 │  ├─Amqp               Amqp消息队列
 │  ├─Annotation         注解 
 │  ├─Aspect             Aop切面 
 │  ├─Common             公共方法 
 │  ├─Constants          常量
 │  ├─Controller         控制器
 │  ├─Crontab            定时任务
 │  ├─Exception          异常处理
 │  ├─Job                队列
 │  ├─Listener           监听
 │  ├─Middleware         中间件
 │  ├─Model              模型
 │  ├─Process            自定义进程
 │  ├─Util               工具类
 │  ├─common.php         函数文件
 ├─bin           
 │  ├─hyperf.php         运行入口文件
 ├─config                应用配置目录
 │  ├─autoload           组件相关配置文件目录
 │     ├─server.php      服务配置文件
 │     ├─redis.php       redis配置文件
 │  ├─config.php         全局配置文件 
 │  ├─container.php      容器配置文件   
 │  ├─routes.php         路由配置文件   
 ├─public                资源文件目录 
 ├─runtime               运行目录
 │  ├─container          容器缓存
 │  ├─logs               日志目录
 │  ├─hyperf.pid         程序进程id
 ├─storage               存储目录 
 │  ├─languages          验证器语言文件目录
 │  ├─view               视图目录 
 ├─test                  单元测试 
 ├─vendor                第三方类库目录（Composer依赖库）
 ├─.env                  环境变量文件
 ├─.gitignore            忽略文件
 ├─composer.json         composer 定义文件
```

#### 运行

1. 安装依赖文件
> composer install
2. 启动
> php bin/hyperf.php start
3.使用到的服务
```
    * redis
    * mysql
    * elasticsearch （es搜索用）
    * rabbitmq （AMQP使用）
    * zipkin （调用链追踪）
```
