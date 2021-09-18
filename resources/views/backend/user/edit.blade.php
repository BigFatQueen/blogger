@extends('backend.backend_template')
@section('content')
<div class="container">
     <div class="row">
        <div class="col">
            <h1 class="text-white">Edit</h1>
        </div>
        <div class="col col-lg-2">
            <a class="btn btn-neutral form-control" href="{{URL::previous()}}">
                <i class="fas fa-backward">  Back</i>
            </a>
        </div>
     </div>
 </div>
<div class="container ">
    <div class="row">
        <div class="col-12 card p-3">    
        @php @endphp    
            <form method="post" action="{{ route('admin.user.update', \App\Helper\Crypt::crypt()->encrypt( $user->id ))}}">
                @csrf
                @method('PUT')
                <div class="row">
                    
                    <div class="col-6">
                        <div class="form-group">
                            <label for="name">User Name:<i class="text-danger">*</i></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{$user->name}}" autofocus id="name">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="email">Email<i class="text-danger">*</i></label>
                            <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{$user->email}}" autofocus id="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group custom-control custom-control-alternative custom-checkbox">
                            <label for="role">Role</label>
                            <div class="row">
                                    @foreach($user->roles as $role)
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            <input class="custom-control-input" id="role-{{$role->id}}" type="checkbox" name="role[]" value="{{$role->id}}"
                                            @php
                                                if ($role->id == $user->role)
                                                echo "checked";
                                            @endphp>
                                            <label class="custom-control-label" for="role-{{$role->id}}">{{$role->name}} &nbsp;&nbsp;&nbsp;</span> </label>
                                        </div>
                                    @endforeach
                                </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group custom-control custom-control-alternative custom-checkbox">
                            <label for="permission">Permissions<i class="text-danger"></i></label>   
                                <div class="row">
                                    @foreach($permissions as $permission)
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            <input class="custom-control-input" id="permission-{{$permission->id}}" type="checkbox" name="permissions[]" value="{{$permission->id}}"
                                            @php
                                                if (in_array($permission->name, $user_permissions))
                                                echo "checked";
                                            @endphp>
                                            <label class="custom-control-label" for="permission-{{$permission->id}}">{{$permission->name}} &nbsp;&nbsp;&nbsp;</span> </label>
                                        </div>
                                    @endforeach
                                </div>
                        </div>
                    </div>
                    <div class="col-12 mb-2">
                        <div class="custom-control custom-control-alternative custom-checkbox">
                            <input type="hidden" class="pwd" name="pwd" value="{{old('change_pwd')}}">
                            <input class="custom-control-input" id="change-pwd" type="checkbox" name="change_pwd" value="1" {{ old('change_pwd') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="change-pwd">
                                <span class="text-muted">Change Password ?</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-6 password d-none">
                        <div class="form-group">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password" placeholder="Enter new Password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6 password d-none">
                        <div class="form-group">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password" placeholder="Enter Password confirmation">
                        </div>
                    </div>
                    
                    <div class="col-12 mb-2">
                        <div class="custom-control custom-control-alternative custom-checkbox">
                            <input class="custom-control-input" id="logout" type="checkbox" name="logout" value="1" {{ old('logout') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="logout">
                                <span class="text-muted">Logout Other Devices ?</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group col-4 offset-4">
                    <input type="submit" value="Update" class="form-control btn btn-primary">
                </div>
            </form>
        </div>
    </div>  
</div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            if ($('.pwd').val()) {
                $('.password').removeClass('d-none')  
            }
            $('input[name="change_pwd"]').click(function(){
                if($(this).prop("checked") == true){
                        $('.password').removeClass('d-none')
                    }
                    else if($(this).prop("checked") == false){
                        $('.password').addClass('d-none')
                    }
                });
        })
    </script>
@endsection