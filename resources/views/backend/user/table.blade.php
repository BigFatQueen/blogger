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
        @foreach($users as $key => $row)
            <tr>
                <td>{{$key + $users->firstItem()}}</td>
                <td>{{ $row->name }} &nbsp; @if(Cache::has('active-' . $row->id)) <span class="badge badge-dot mr-4"><i class="bg-success"></i> @endif</td> 
                <td>{{ $row->email }}</td>                  
                <td>@if($row->role == 1) <span class="badge badge-primary">Admin</span> @elseif($row->role == 2) <span class="badge badge-primary">Creator</span> @else <span class="badge badge-primary">User</span> @endif</td>     
                @php $newDateTime = date('d-m-Y h:i A', strtotime($row->last_seen)); @endphp  
                <td>
                    @if(isset($row->roles))
                        @foreach($row->roles as $role)
                            @foreach($role->permissions as $permission)
                                <span class="badge badge-primary">{{$permission->name}}</span>
                            @endforeach
                        @endforeach
                    @endif
                </td>       
                <td>@if(isset($row->last_seen)) {{ $newDateTime }} @else New User @endif</td>       
                <td>
                    <a href="{{route('admin.user.edit',\App\Helper\Crypt::crypt()->encrypt($row->id))}}" class="btn btn-outline-primary btn-sm">Edit</a>
                    <form  method="post" action="{{route('admin.user.inactive', \App\Helper\Crypt::crypt()->encrypt($row->id))}}" class="d-inline-block" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="status" value="{{ ($row->status == 1) ? 1 : 0 }}">
                        <input type="submit" class="btn btn-outline-primary btn-sm" value="{{ ($row->status == 1) ? 'In Active' : 'Active' }}">
                    </form>
                    <form  method="post" action="{{route('admin.user.destroy', \App\Helper\Crypt::crypt()->encrypt($row->id))}}" class="d-inline-block" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <input type="submit" class="btn btn-outline-primary btn-sm" value="Delete">
                    </form>
                </td>                          
            </tr>
        @endforeach
    </tbody>
</table>
    <!-- Card footer -->
    <div class="card-footer py-4">
        <div class="row">
            <div class="col-12">
                <div class=" float-right">
                    @if($users)
                        {{ $users->render() }}
                    @endif
                </div>
            </div>
        </div>
    </div>