
<table class="table align-items-center table-flush">
    <thead>
        <tr>
            <th>No.</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Permissions</th>
            <th>Last Time Login</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if (count($members))
        @foreach($members as $key => $row)
            <tr>
                <td>{{$key + $members->firstItem()}}</td>
                <td>{{ $row->name }} &nbsp; @if(Cache::has('active-' . $row->id)) <span class="badge badge-dot mr-4"><i class="bg-success"></i> @endif</td> 
                <td>{{ $row->email }}</td>                  
                <td><span class="badge badge-primary">{{$row->role->name}}</span></td>     
                @php $newDateTime = date('d-m-Y h:i A', strtotime($row->last_seen)); @endphp  
                <td>
                    @if(isset($row->role->permissions))
                        @foreach($row->role->permissions as $permission)
                            <span class="badge badge-primary">{{$permission->name}}</span>
                        @endforeach
                    @else
                        @foreach($row->roles as $role)
                            @foreach($role->permissions as $permission)
                                <span class="badge badge-primary">{{$permission->name}}</span>
                            @endforeach
                        @endforeach
                    @endif
                </td>       
                <td>@if(isset($row->last_seen)) {{ $newDateTime }} @else New User @endif</td>       
                <td>
                    <a href="{{route('admin.member.edit',\App\Helper\Crypt::crypt()->encrypt($row->id))}}" class="btn btn-outline-primary btn-sm">Edit</a>
                    <a href="{{route('admin.member.show',\App\Helper\Crypt::crypt()->encrypt($row->id))}}" class="btn btn-outline-success btn-sm">Show</a>
                    <form  method="post" action="{{route('admin.member.inactive', \App\Helper\Crypt::crypt()->encrypt($row->id))}}" class="d-inline-block" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="status" value="{{ ($row->status == 1) ? 0 : 1 }}">
                        <input type="submit" class="btn btn-outline-warning btn-sm" value="{{ ($row->status == 1) ? 'In Active' : 'Active' }}">
                    </form>
                    <form  method="post" action="{{route('admin.member.destroy', \App\Helper\Crypt::crypt()->encrypt($row->id))}}" class="d-inline-block" onsubmit="return confirm('Are you sure?')">
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
                @if($members)
                    {{ $members->render() }}
                @endif
            </div>
        </div>
    </div>
</div>
@php $role_id = null; $permission_id = null; $keyword = null;
    if(isset($filter_arr)){
       $role_id = $filter_arr['role_id']; 
       $permission_id = $filter_arr['permission_id']; 
       $keyword = $filter_arr['keyword']; 
    }
    @endphp
    
    <input type="hidden" name="" class="filter" data-role-id="{{$role_id}}" data-permission-id="{{$permission_id}}" data-keyword="{{$keyword}}">