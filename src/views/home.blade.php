@extends('adminamazing::teamplate')

@section('pageTitle', 'Пользователи')
@section('content')
    <div class="row">
        <!-- Column -->
        <div class="col-12">
            <div class="card">
                <div class="card-block">
                    <h4 class="card-title">@yield('pageTitle')</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Имя</th>
                                    <th>Email</th>
                                    <th>Дата регистрации</th>
                                    <th class="text-nowrap">Действие</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{$user->id}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->created_at}}</td>                                        
                                        <td class="text-nowrap">                                            
                                            <form action="{{ route('AdminUsersDeleted', $user->id) }}" method="POST">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                 <a href="{{ route('AdminUsersEdit', $user->id) }}" data-toggle="tooltip" data-original-title="Редактировать"> <i class="fa fa-pencil text-inverse m-r-10"></i> </a>
                                                <button class="btn btn-link" data-toggle="tooltip" data-original-title="Удалить"><i class="fa fa-close text-danger"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
            <nav aria-label="Page navigation example" class="m-t-40">
                {{ $users->links('vendor.pagination.bootstrap-4') }}
            </nav>
        </div>
        <!-- Column -->    
    </div>
@endsection