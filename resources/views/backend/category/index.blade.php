 @extends('backend.backend_template')
 @section('content')
 <div class="container">
     <div class="row">
         <div class="col">
            <h1 class="text-white">List</h1>
         </div>
         <div class="col-3 col-sm-3 col-md-2 col-lg-2">
             <a class="btn btn-neutral form-control" href="{{route('admin.category.create')}}">
                <i class="fas fa-plus"> Add</i>
             </a>
         </div>
     </div>
 </div>
 <div class="container">      
     <div class="row">
        @if (session('status'))
            <div class="alert alert-primary col-md-6 offset-3">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              {{ session('status') }}
            </div>
        @endif
         <div class="col-lg-12 p-3 card table-responsive">
                <table class="table align-items-center table-flush myTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Category Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $key => $row)
                            <tr>
                                <td>{{$key +1}}</td>
                                <td>{{ $row->name }}</td>                   
                                <td>
                                    <a href="{{route('admin.category.edit',\App\Helper\Crypt::crypt()->encrypt($row->id))}}" class="btn btn-outline-primary btn-sm">Edit</a>
                                    <form  method="post" action="{{route('admin.category.destroy', \App\Helper\Crypt::crypt()->encrypt($row->id))}}" class="d-inline-block" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <input type="submit" class="btn btn-outline-primary btn-sm" value="Delete">
                                    </form>
                                </td>                          
                            </tr>
                        @endforeach
                    </tbody>
                 </table>
         </div>
     </div>
 </div>
@endsection