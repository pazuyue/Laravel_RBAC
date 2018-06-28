<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About Laravel

####功能测试
根据前面的 isAdmin 中间件实现逻辑，系统第一个用户默认具备管理员权限，这样我们就可以通过这个用户创建必要的权限和角色。

在 http://permission.test/permissions 页面新增四个权限 —— Create Post、Edit Post、Delete Post 以及 Administer roles & permissions      

接下来在 http://permission.test/roles 页面创建几个具备相应权限的角色        

最后在 http://permission.test/users 页面将「Admin」角色分配给当前登录用户
