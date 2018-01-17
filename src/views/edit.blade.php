@extends('adminamazing::teamplate')

@section('pageTitle', 'Редактирование пользователя')
@section('content')
    <div class="row">
        <!-- Column -->
        <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-block"> 
                    <small class="text-muted">Email </small>
                    <h6>{{$edituser->email}}</h6>
                    <small class="text-muted">Дата регистрации </small>
                    <h6>{{$edituser->created_at}}</h6>
                    <small class="text-muted">Последнее обновление профиля </small>
                    <h6>{{$edituser->updated_at}}</h6>
                    @if(count($LoginLogs) > 0)
                        <small class="text-muted">Последний вход </small>
                        <h6>{{$LoginLogs[0]->created_at}}</h6>
                    @endif
                    <h3><a target="_blank" href="{{route('AdminOperations', ['user_email' => $edituser->email])}}">Операции</a></h3>
                </div>
            </div>
            <div class="col-sm-12">
                <form action="{{ route('AdminUsersLoginWith', $edituser->id) }}" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <button class="btn btn-success">Войти под пользователем</button>
                </form>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-8 col-xlg-9 col-md-7"> 
            <div class="card">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs profile-tab" role="tablist">
                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#settings" role="tab">Настройки</a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#loginlog" role="tab">Логи авторизации</a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#wallets" role="tab">Кошельки</a> </li>
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
                            <form action="{{route('AdminUsersUpdate', $edituser->id)}}" method="POST" class="form-horizontal form-material">
                                <div class="form-group">
                                    <label for="name" class="col-md-12">Имя</label>
                                    <div class="col-md-12">
                                        <input type="text" name="name" id="name" value="{{$edituser->name}}" placeholder="" class="form-control form-control-line">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-md-12">Email</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{$edituser->email}}" placeholder="admin@admin.com" class="form-control form-control-line" name="email" id="email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="new_password" class="col-md-12">Новый пароль</label>
                                    <div class="col-md-12">
                                        <input type="password" id="new_password" name="new_password" value="" class="form-control form-control-line">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="aff_ref" class="col-md-12">Реферальный суфикс</label>
                                    <div class="col-md-12">
                                        <input type="text" id="aff_ref" name="aff_ref" value="{{$edituser->aff_ref}}" class="form-control form-control-line">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="custom-control custom-checkbox">
                                            <input name="representative" {{($edituser->representative)?'checked':''}} type="checkbox" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Сделать представителем (выше рефка)</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="custom-control custom-checkbox">
                                            <input name="google2fa_status" {{($edituser->google2fa_status)?'checked':''}} type="checkbox" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Двухфакторная авторизация</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="google2fa_secret" class="col-md-12">2FA секретный ключ</label>
                                    <div class="col-md-12">
                                        <input type="text" id="google2fa_secret" name="google2fa_secret" value="{{$edituser->google2fa_secret}}" class="form-control form-control-line">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="google2fa_ts" class="col-md-12">2FA ts</label>
                                    <div class="col-md-12">
                                        <input type="text" id="google2fa_ts" name="google2fa_ts" value="{{$edituser->google2fa_ts}}" class="form-control form-control-line">
                                    </div>
                                </div>
                                @if(in_array('adminrole', $accessible))
                                    <div class="form-group">
                                        <label for="role" class="col-md-12">Изменить роль</label>
                                        <div class="col-md-12">
                                            <select class="custom-select col-12" id="role" name="selected_role">
                                                <option value = "not_selected">Не выбрано</option>
                                                {{!! $list_roles !!}}
                                            </select>
                                        </div>
                                    </div>
                                @endif                    
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

                    <div class="tab-pane" id="loginlog" role="tabpanel">
                        <div class="card-block">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Дата</th>
                                            <th>Браузер</th>
                                            <th>IP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($LoginLogs as $user)
                                            <tr>
                                                <td>{{$user->id}}</td>
                                                <td>{{$user->created_at}}</td>
                                                <td>{{$user->browser}}</td>
                                                <td>{{$user->ip}}</td>       
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="wallets" role="tabpanel">
                        <div class="card-block">
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger">
                                        {{ $error }}
                                    </div>
                                @endforeach
                            @endif
                            <form action="{{route('AdminUsersSaveWallet', $edituser->id)}}" method="POST" class="form-horizontal form-material">
                                @if($wallets)
                                    @foreach($wallets as $row)
                                        <div class="form-group">
                                            <label for="name{{$row->id}}" class="col-md-12">{{$row->title}}, {{$row->currency}}</label>
                                            <div class="col-md-12">
                                                <input type="text" name="wallet[{{$row->id}}]" id="name{{$row->id}}" value="{{$row->wallet}}" placeholder="" class="form-control form-control-line">
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                {{ csrf_field() }}
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button class="btn btn-success">Сохранить кошельки</button>
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