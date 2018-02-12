@extends('adminamazing::teamplate')

@section('pageTitle', 'Структура пользователя')
@section('content')
    @push('scripts')
        <script>
            var route = '{{ route('AdminUsersDelete') }}';
            message = 'Вы точно хотите удалить данного пользователя?';
        </script>
    @endpush
    <div class="row">
        <!-- Column -->
        @foreach($levels as $row)
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="row">
                        <div class="col-12">
                            <div class="social-widget">
                                <a href="{{ route('AdminUsersStructure', ['user' => $user->id, 'level' => $row->level]) }}"><div class="soc-header box-linkedin">Уровень {{$row->level}}</div></a>
                                <div class="soc-content">
                                    <div class="4 b-r">
                                        <h3 class="font-medium">{{$row->count}}</h3>
                                        <h5 class="text-muted">Пользователей</h5></div>
                                    <div class="4 b-r">
                                        <h3 class="font-medium">${{$row->invested}}</h3>
                                        <h5 class="text-muted">Пополнения</h5></div>
                                    <div class="4">
                                        <h3 class="font-medium">${{$row->withdraw}}</h3>
                                        <h5 class="text-muted">Вывод</h5></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
        @endforeach
    </div>

    <div class="row">
        <!-- Column -->
        <div class="col-12">
            <div class="card">
                <div class="card-block">
                    <h4 class="card-title">@yield('pageTitle') - <a href="{{ route('AdminUsersEdit', $user->id) }}">{{$user->email}}</a>, Уровень {{$level}}</h4>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Имя</th>
                                    <th>Email</th>
                                    <th>Вышестоящий</th>
                                    <th>Дата регистрации</th>
                                    <th class="text-nowrap">Действие</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{$user->info->id}}</td>
                                        <td>{{$user->info->name}}</td>
                                        <td><a href="{{ route('AdminUsersEdit', $user->info->id) }}">{{$user->info->email}}</a></td>
                                        <td><a href="{{ route('AdminUsersEdit', $user->info->upline->id) }}">{{$user->info->upline->email}}</a></td>
                                        <td>{{$user->info->created_at}}</td> 
                                        <td class="text-nowrap">
                                            <a href="{{ route('AdminUsersEdit', $user->info->id) }}" data-toggle="tooltip" data-original-title="Редактировать"> <i class="fa fa-pencil text-inverse m-r-10"></i> </a>
                                            <a href="#deleteModal" class="delete_toggle" data-id="{{ $user->info->id }}" data-toggle="modal"><i class="fa fa-close text-danger"></i></a>
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