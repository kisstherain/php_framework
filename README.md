#### 简单的php框架

1. core 基础代码,包括:
    主对象Main
    控制器基类Controller
    配置管理Config
    工具类Util
    动态加载 Autoloader
    路由解析类Router
    视图类View
2. public 静态资源，用于存放静态资源，css、js、img等

3. apps 应用代码
    config 应用配置
    controller 应用控制器
    model 应该模型
    view 应用视图

框架特点：
    框架系统配置在config/main.php中，包括各种静态资源路径，及app信息配置
    动态加载类已经注册了app地址，可以直接用命名空间调用类
    路由配置使用key=>value形式，手动配置更加灵活，可自定义参数

环境搭建：
    1.rewrite请求到index.php



前端源文件存储在src中，生产环境可在nginx进行保护，禁止直接访问
src
    app     应用
    common  公共组件
    lib     公共库

public中均可直接访问

