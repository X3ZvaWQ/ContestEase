@extends('layouts.app')

@section('template')

<div class="container">
    <div style="width:100%;">
        <div class="text-center text-muted">
            <h5 class="pb-title"><i class="MDI script"></i> SAST 评分系统</h5>
            <small>请使用ATSAST账号登陆</small>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-3"></div>
            <div class="col-sm-12 col-md-6">
                <paper-card class="mb-3">
                    <form class="needs-validation" action="{{ route('login') }}" method="post" novalidate>
                        @csrf
                        <div class="form-group">
                            <label for="email" class="bmd-label-floating">用户名</label>
                            <input type="text" class="form-control" name="email" id="email" required>
                            @if ($errors->has('email'))
                                <span role="alert" class="text-danger" style="font-size: .85rem">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="password" class="bmd-label-floating">密码 (邮箱全部小写)</label>
                            <input type="password" class="form-control" name="password" id="password" required>
                            @if ($errors->has('password'))
                                <span role="alert" class="text-danger" style="font-size: .85rem">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <input type="hidden" name="remember" value="1">
                        <div class="text-right">
                            <button type="submit" id="pb_submit" class="btn btn-outline-info">登录</button>
                        </div>
                    </form>
                </paper-card >
            </div>
            <div class="col-sm-12 col-md-3"></div>
        </div>
    </div>
</div>
<script>
    function login(){
        $.post("core/ajax.php",{
            action:"login",
            name:$("#name").val(),
            pass:$("#pass").val(),
        },function(result){
            result=JSON.parse(result);
            $.snackbar({content: result.desc,style:"toast text-center atsast-toast"});
            if(result.ret=="200"){
                location.href="./";
            }
        });
    }

</script>

@endsection
