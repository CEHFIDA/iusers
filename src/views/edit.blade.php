@extends('adminamazing::teamplate')

@section('pageTitle', 'Редактирование пользователя')
@section('content')
    <div class="row">
        <!-- Column -->
        <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-block"> 
                    <small class="text-muted">Email address </small>
                    <h6>{{$user->email}}</h6>
                    <small class="text-muted">Дата регистрации </small>
                    <h6>{{$user->created_at}}</h6>
                    <small class="text-muted">Последнее обновление профиля </small>
                    <h6>{{$user->updated_at}}</h6>
                </div>
            </div>
            <div class="col-sm-12">
                <button class="btn btn-success">Войти под пользователем</button>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-8 col-xlg-9 col-md-7">
            <div class="card">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs profile-tab" role="tablist">
                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#settings" role="tab">Настройки</a> </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    
                    <!--second tab-->
                    
                    <div class="tab-pane active" id="settings" role="tabpanel">
                        <div class="card-block">
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger">
                                        {{ $error }}
                                    </div>
                                @endforeach
                            @endif
                            <form action="{{route('AdminUsersUpdate', $user->id)}}" method="POST" class="form-horizontal form-material">
                                <div class="form-group">
                                    <label for="name" class="col-md-12">Имя</label>
                                    <div class="col-md-12">
                                        <input type="text" name="name" id="name" value="{{$user->name}}" placeholder="" class="form-control form-control-line">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-md-12">Email</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{$user->email}}" placeholder="admin@admin.com" class="form-control form-control-line" name="email" id="email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="new_password" class="col-md-12">Новый пароль</label>
                                    <div class="col-md-12">
                                        <input type="password" id="new_password" name="new_password" value="" class="form-control form-control-line">
                                    </div>
                                </div>
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button class="btn btn-success">Обновить профиль</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
    </div>
@endsection