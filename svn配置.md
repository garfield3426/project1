# centos服务器下安装配置svn #
#####1、安装 执行

yum -y install subversion 

#####2、安装好了之后 新建一个svn目录

mkdir /home/svn

#####3、新建一个版本仓库

svnadmin create /home/svn/demo

#####4、导入本地一个目录(www.demo.com)到库中(demo)。但是导入后，本地的目录并不会处于受控状态。

svn import /pkg/web/www.demo.com svn://localhost/demo -m “add code for pr021 web”

#####5、添加用户

要添加SVN用户非常简单，只需在/home/svn/demo/conf/passwd文件添加一个形如“username=password”的条目就可以了。为了测试，我添加了如下内容:

[users]
harry = harryssecret

maorq = 123456

pm = pm_pw

#####6、修改用户访问策略

/home/svn/demo/conf/authz记录用户的访问策略，以下是参考:

[groups]

project_p = pm

[demo:/] 如果你的仓库下边就一个版本的话 就不需要使用xxx 直接一个斜杠就完事

[demo:/xxx] 如果你的仓库下边有多个版本的话xxx就是那个文件夹的名字

\#上边这2个根据自己情况选一个

@project_p = rw

\* =

说明：以上信息表示，只有project_p用户组有根目录的读写权。r表示对该目录有读权限，w表示对该目录有写权限，rw表示对该目录有读写权限。最后一行的* =表示，除了上面设置了权限的用户组之外，其他任何人都被禁止访问本目录。这个很重要，一定要加上！

#####7、修改svnserve.conf文件,让用户和策略配置升效.
svnserve.conf内容如下:

[general]

anon-access = none

auth-access = write

password-db = passwd

authz-db =  authz

#####8、启动服务器

svnserve -d -r /home/svn

#####9、服务器上导版本库

svn co svn://localhost/demo  /pkg/web/www.demo.com --username maorq --password 123456

#####10、服务器上删除内容并提交到版本库

svn del a.html

svn ci -m 'del a file'


##问题：

#####1、将项目导致版本库时报错：svn: Error converting entry in directory ‘.’ to UTF-8.

解决办法：export LANG="en_US.UTF-8"

#####2、点击“与前一个版本比较”报错“unreadable path encountered； access denied”

解决办法:编辑[代码仓库目录下]/conf/svnserve.conf

1、将[anon-access]的值设置为[none]。如下：

[general]

\### These options control access to the repository for unauthenticated

\### and authenticated users.  Valid values are "write", "read",

\### and "none".  The sample settings below are the defaults.

\# anon-access = read

anon-access = none

2、重启SVN服务了。

killall -HUP svnserve

svnserve -d -r /alidata/svn



