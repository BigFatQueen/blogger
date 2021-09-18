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
            <form method="post" action="{{ route('admin.role.update', \App\Helper\Crypt::crypt()->encrypt( $role->id ))}}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="name">Role Name:<i class="text-danger">*</i></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{$role->name}}" autofocus id="name">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group custom-control custom-control-alternative custom-checkbox">
                            <label for="name">Permissions<i class="text-danger"></i></label>   
                                <div class="row">
                                    @foreach($permissions as $permission)
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            <input class="custom-control-input" id="{{$permission->id}}" type="checkbox" name="permissions[]" value="{{$permission->id}}"
                                            @php
                                                if (in_array($permission->name, $user_permissions))
                                                echo "checked";
                                            @endphp>
                                            <label class="custom-control-label" for="{{$permission->id}}">{{$permission->name}} &nbsp;&nbsp;&nbsp;</span> </label>
                                        </div>
                                    @endforeach
                                </div>
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