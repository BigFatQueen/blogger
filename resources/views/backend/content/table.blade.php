
<table class="table align-items-center table-flush">
    <thead>
        <tr>
            <th>No.</th>
            <th>Title</th>
            <th>Category</th>
            <th>Content</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if (count($contents))
        @foreach($contents as $key => $row)
            <tr>
                <td>{{$key + $contents->firstItem()}}</td>
                <td>{{ Str::limit ($row->title, 30)}}</td> 
                <td>{{ $row->category->name }}</td> 
                <td>{{ Str::limit ($row->content, 30) }}</td>                    
                <td>
                    @php $images = json_decode($row->image); @endphp
                    @if($images != null)
                        <img src="{{ asset('/storage/'.$images[0]) }}" alt="Image placeholder" class="img-fluid w-100">
                    @endif
                </td>            
                <td>
                    <a href="{{route('admin.content.edit',\App\Helper\Crypt::crypt()->encrypt($row->id))}}" class="btn btn-outline-primary btn-sm">Edit</a>
                    <a href="{{route('admin.content.show',\App\Helper\Crypt::crypt()->encrypt($row->id))}}" class="btn btn-outline-success btn-sm">Show</a>
                    <form  method="post" action="{{route('admin.content.inactive', \App\Helper\Crypt::crypt()->encrypt($row->id))}}" class="d-inline-block" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="status" value="{{ ($row->status == 1) ? 0 : 1 }}">
                        <input type="submit" class="btn btn-outline-warning btn-sm" value="{{ ($row->status == 1) ? 'In Active' : 'Active' }}">
                    </form>
                    <form  method="post" action="{{route('admin.content.destroy', \App\Helper\Crypt::crypt()->encrypt($row->id))}}" class="d-inline-block" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <input type="submit" class="btn btn-outline-danger btn-sm" value="Delete">
                    </form>
                </td>                          
            </tr>
        @endforeach
        @else
        <tr>
            <td colspan="7" class="text-center">No matching records found</td>
        </tr>
        @endif
    </tbody>
</table>
<!-- Card footer -->
<div class="card-footer py-4">
    <div class="row">
        <div class="col-12">
            <div class=" float-right">
                @if($contents)
                    {{ $contents->render() }}
                @endif
            </div>
        </div>
    </div>
</div>
@php $creator_id = null; $category_id = null; $keyword = null;
    if(isset($filter_arr)){
       $creator_id = $filter_arr['creator_id']; 
       $category_id = $filter_arr['category_id']; 
       $keyword = $filter_arr['keyword']; 
    }
    @endphp
    
    <input type="hidden" name="" class="filter" data-creator-id="{{$creator_id}}" data-category-id="{{$category_id}}" data-keyword="{{$keyword}}">