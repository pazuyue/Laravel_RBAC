@extends('layouts.app')


@section('content')

    <div class="col-lg-10 col-lg-offset-1">
        <h1><i class="fa fa-key"></i> 角色管理

            <a href="{{ route('users.index') }}" class="btn btn-default pull-right">用户列表</a>
            <a href="{{ route('permissions.index') }}" class="btn btn-default pull-right">权限列表</a></h1>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>角色</th>
                    <th>权限</th>
                    <th>操作</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($roles as $role)
                    <tr>

                        <td>{{ $role->name }}</td>

                        <td><ul>@foreach ($role->permissions()->pluck('name') as $name)  <li>{{ $name }}</li>  @endforeach</ul></td>{{-- Retrieve array of permissions associated to a role and convert to string --}}
                        <td>
                            <a href="{{ URL::to('roles/'.$role->id.'/edit') }}" class="btn btn-info pull-left" style="margin-right: 3px;">修改</a>

                            {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id] ]) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger']) !!}
                            {!! Form::close() !!}

                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>

        <a href="{{ URL::to('roles/create') }}" class="btn btn-success">添加角色</a>

    </div>

@endsection