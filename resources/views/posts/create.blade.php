<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

</head>
<body>
<div id="app">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- 导航条左边 -->
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    @if (!Auth::guest())
                        <li><a href="{{ route('posts.create') }}">New Article</a></li>
                    @endif
                </ul>

                <!-- 导航条右边 -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- 登录注册链接 -->
                    @if (Auth::guest())
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    @role('Admin') {{-- Laravel-permission blade 辅助函数 --}}
                                    <a href="#"><i class="fa fa-btn fa-unlock"></i>Admin</a>
                                    @endrole
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @if(Session::has('flash_message'))
        <div class="container">
            <div class="alert alert-success"><em> {!! session('flash_message') !!}</em>
            </div>
        </div>
    @endif
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @include ('errors.list') {{-- 引入错误文件 --}}
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                <h1>新建文章</h1>
                <hr>

                {{-- 使用 Laravel HTML Form Collective 创建表单 --}}
                {{ Form::open(array('route' => 'posts.store')) }}

                <div class="form-group">
                    {{ Form::label('title', '标题') }}
                    {{ Form::text('title', null, array('class' => 'form-control')) }}
                    <br>

                    {{ Form::label('body', '文章内容') }}
                    {{-- {{ Form::textarea('body', null, array('class' => 'form-control')) }}--}}
                    <textarea id="summernote" name="body"></textarea>
                    <br>

                    {{ Form::submit('Create Post', array('class' => 'btn btn-success btn-lg btn-block')) }}
                    {{ Form::close() }}
                    <script>
                        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

                        jQuery('#summernote').summernote({
                            height: 450,
                            styleTags: ['p', 'blockquote', 'pre', 'h3', 'h4', 'h5'],
                            callbacks: {
                                onImageUpload: function(files) {
                                    var data = new FormData();
                                    data.append("image", files[0]);
                                    jQuery.ajax({
                                        data: data,
                                        type: 'POST',
                                        url: 'upload',
                                        cache: false,
                                        contentType: false,
                                        processData: false,
                                        statusCode: {
                                            200: function (data) {
                                                console.log(data.image)
                                                if (data.success) {
                                                    jQuery('#summernote').summernote('insertImage', data.image.path, data.image.desc);
                                                } else {
                                                    alert(data.message);
                                                }
                                            },
                                            422: function (data) {
                                                var result = jQuery.parseJSON(data.responseText);
                                                jQuery.each(result.errors, function (field, errors) {
                                                    jQuery.each(errors, function (index, message) {
                                                        alert(message);
                                                    })
                                                });
                                            },
                                            404: function () {
                                                alert('操作对象不存在');
                                            },
                                            403: function () {
                                                alert('你没有权限进行上传操作');
                                            },
                                            413: function () {
                                                alert('上传图片大小超出服务器承载极限');
                                            },
                                            401: function () {
                                                alert('你没有权限进行上传操作，请先登录');
                                            },
                                            500: function () {
                                                alert('服务器开小差了，请在线联系学院君');
                                            }
                                        }
                                    });
                                }
                            }
                        });

                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>