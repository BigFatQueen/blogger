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
            <form method="post" action="{{ route('admin.member.update', \App\Helper\Crypt::crypt()->encrypt( $content->id ))}}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                        <div class="form-group">
                            <label class="form-control-label">Category</label>
                            <select name="creator_id" id="" class="form-control creator_id">
                                <option value="">Choose One Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{$category->id}}" {{ ($category->id == $content->category_id) ? 'selected' : '' }}>{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                        <div class="form-group">
                            <label class="form-control-label">Name</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Enter Title" value="{{$content->name}}">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                        <div class="form-group">
                            <label class="form-control-label">Text</label>
                            <textarea rows="4" class="form-control @error('text') is-invalid @enderror" name="text" placeholder="A few words about you ...">{{$content->text}}</textarea>
                            @error('text')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                        <div class="form-group">
                            <label class="form-control-label">Audio</label>
                            <textarea rows="4" class="form-control @error('text') is-invalid @enderror" name="text" placeholder="A few words about you ...">{{$content->text}}</textarea>
                            @error('text')
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