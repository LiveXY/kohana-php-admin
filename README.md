# kohana-php-admin
基于kohana-php的后台管理 支持ie6+/ff/opera/chrome/mobile

###下载代码并设置目录权限
```
git clone https://github.com/LiveXY/kohana-php-admin.git
cd kohana-php-admin/
mkdir application/logs/
mkdir application/cache/
chmod a+w application/logs/
chmod a+w application/cache/
chmod a+w database/
chmod a+w database/test.db
```
###修改sqlite数据库连接配置
```
application/config/database.php #修改数据库路径
```
###centos6安装php的sqlite3/memcache组件
```
yum install php-pecl-memcache php-pecl-sqlite

```
###配置nginx或apache

###效果
![图片](https://raw.githubusercontent.com/LiveXY/kohana-php-admin/master/20150530192639.jpg)
![图片](https://raw.githubusercontent.com/LiveXY/kohana-php-admin/master/20150530192708.jpg)
![图片](https://raw.githubusercontent.com/LiveXY/kohana-php-admin/master/20150530192738.jpg)
![图片](https://raw.githubusercontent.com/LiveXY/kohana-php-admin/master/20150530192838.jpg)
