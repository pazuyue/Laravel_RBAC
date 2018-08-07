@extends('layouts.app')



@section('content')

    <div class="container">

        <h1>{{ $post->title }}</h1>
        <hr>
        <p class="lead">{{ $post->body }} </p>
        <hr>
        {!! Form::open(['method' => 'DELETE', 'route' => ['posts.destroy', $post->id] ]) !!}
        <a href="{{ url()->previous() }}" class="btn btn-primary">返回</a>
        @can('修改文章')
            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-info" role="button">修改</a>
        @endcan
        @can('删除文章')
            {!! Form::submit('删除', ['class' => 'btn btn-danger']) !!}
        @endcan
        {!! Form::close() !!}

    </div>

@endsection