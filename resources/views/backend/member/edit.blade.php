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
            <form method="post" action="{{ route('admin.member.update', \App\Helper\Crypt::crypt()->encrypt( $user->id ))}}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    
                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6">
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
                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6">
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
                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="phone_1">Login Phone Number<i class="text-danger"></i></label>
                            <input type="text" name="phone_1" class="form-control @error('phone_1') is-invalid @enderror" value="{{$user->phone_no}}" autofocus id="phone_1">
                            @error('phone_1')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group custom-control custom-control-alternative custom-checkbox">
                            <label for="role">Role</label>
                            <div class="row">
                                    @foreach($user->roles as $role)
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            <input class="custom-control-input" id="role-{{$role->id}}" type="checkbox" name="role[]" value="{{$role->id}}"
                                            @php
                                                if ($role->id == $user->role_id)
                                                echo "checked";
                                            @endphp>
                                            <label class="custom-control-label" for="role-{{$role->id}}">{{$role->name}} &nbsp;&nbsp;&nbsp;</span> </label>
                                        </div>
                                    @endforeach
                                    <input type="hidden" name="role_id" value="{{$user->role_id}}">
                                </div>
                        </div>
                    </div>
                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6">
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
                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 password d-none">
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
                    
                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 mt-2">
                        <div class="form-group custom-control custom-control-alternative custom-checkbox">
                            <label for="category">Categories<i class="text-danger"></i></label>   
                                <div class="row">
                                    @foreach($categories as $category)
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            <input class="custom-control-input" id="category-{{$category->id}}" type="checkbox" name="categories[]" value="{{$category->id}}"
                                            @php
                                                if (in_array($category->name, $user_categories))
                                                echo "checked";
                                            @endphp>
                                            <label class="custom-control-label" for="category-{{$category->id}}">{{$category->name}} &nbsp;&nbsp;&nbsp;</span> </label>
                                        </div>
                                    @endforeach
                                </div>
                        </div>
                    </div>
                </div>
                <hr class="my-4" />
                <h6 class="heading-small text-muted mb-4">Other information</h6>
                <div class="row">
                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="phone_2">Phone Number<i class="text-danger"></i></label>
                            <input type="text" name="phone_2" class="form-control @error('phone_2') is-invalid @enderror" value="{{$user->userInfo->phone_no}}" autofocus id="phone_2">
                            @error('phone_2')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="dob">Date of Birth<i class="text-danger"></i></label>
                            <input type="date" name="dob" class="form-control @error('dob') is-invalid @enderror" value="{{$user->userInfo->dob}}" autofocus id="dob">
                            @error('dob')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="input_cover_photo">Cover Photo<i class="text-danger"></i></label>
                            <input type="hidden" name="cover_photo" value="{{$user->userInfo->cover_photo}}">
                            @if ($user->userInfo->cover_photo)
                                <img src="{{ asset('/storage/'.$user->userInfo->cover_photo) }}" class="img-fluid" id="cover_photo">
                            @endif
                            <input type="file" name="cover_photo" class="form-control @error('cover_photo') is-invalid @enderror" id="input_cover_photo" onchange="loadCoverPhoto(event)">
                            <img id="show_cover_photo" class="img-fluid">
                            @error('cover_photo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="input_profile_image">Profile Image<i class="text-danger"></i></label>
                            <input type="hidden" name="profile_image" value="{{$user->userInfo->profile_image}}">
                            @if ($user->userInfo->profile_image)
                                <img src="{{ asset('/storage/'.$user->userInfo->profile_image) }}" class="img-fluid" id="profile_image">
                            @endif
                            <input type="file" name="profile_image" class="form-control @error('profile_image') is-invalid @enderror" id="input_profile_image" onchange="loadProfileImage(event)">
                            <img id="show_profile_image" class="img-fluid">
                            @error('profile_image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                        <div class="form-group">
                            <label class="form-control-label" for="description">Description</label>
                            <textarea id="description" rows="4" class="form-control @error('description') is-invalid @enderror" name="description" placeholder="A few words about you ...">{{$user->userInfo->creator->description}}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
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
        var loadCoverPhoto = function(event) {
                var show_image = document.getElementById('cover_photo')
                if (show_image != null) {
                    show_image.removeAttribute('src');
                    show_image.parentNode.removeChild(show_image);
                }
                var cover_photo = document.getElementById('show_cover_photo')
                cover_photo.src = URL.createObjectURL(event.target.files[0]);
            
            };
        var loadProfileImage = function(event) {
            var show_image = document.getElementById('profile_image')
            if (show_image != null) {
                show_image.removeAttribute('src');
                show_image.parentNode.removeChild(show_image);
            }
            var profile_image = document.getElementById('show_profile_image')
            profile_image.src = URL.createObjectURL(event.target.files[0]);
        
        };
    </script>
@endsection