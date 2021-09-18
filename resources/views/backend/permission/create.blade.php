@extends('backend.backend_template')
@section('content')
<div class="container">
     <div class="row">
         <div class="col">
            <h1 class="text-white">Create</h1>
         </div>
         <div class="col col-lg-2">
             <a class="btn btn-neutral form-control " href="{{route('admin.permission.index')}}">
                <i class="ni ni-bullet-list-67">List</i>
             </a>
         </div>
     </div>
 </div>
<div class="container">
    <div class="row">
        <div class="col-12 card p-3">
            <form action="{{route('admin.permission.store')}}" method="post" >
                @csrf
                <div class="form-group">
                    <label for="name">Permission Name<i class="text-danger">*</i></label>   
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>        
                <div class="form-group col-4 offset-4">
                    <input type="submit" value="Save" class="form-control btn btn-primary">
                </div>
            </form>    
        </div>
    </div>    
</div>
@endsection