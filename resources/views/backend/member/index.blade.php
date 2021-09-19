 @extends('backend.backend_template')
 @section('content')
 <div class="container">      
     <div class="row">
        @if (session('status'))
            <div class="alert alert-primary col-md-6 offset-3">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              {{ session('status') }}
            </div>
        @endif
         <div class="col-lg-12 p-3 card">
            <div class="container mb-2">
                <div class="row">
                    <div class="col-12 col-xs-12 col-sm-6 com-md-4 col-lg-4 col-xl-4 mb-2">
                        <select name="permission_id" id="" class="form-control permission_id">
                            <option value="">Choose One Permission</option>
                            @foreach ($permissions as $permission)
                                <option value="{{$permission->id}}">{{$permission->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-xs-12 col-sm-6 com-md-4 col-lg-4 col-xl-4 mb-2">
                        <input type="text" name="keyword" placeholder="Search Keyword..." class="form-control keyword">
                    </div>
                    <div class="col-12 col-xs-12 col-sm-6 com-md-4 col-lg-4 col-xl-4">
                        <input type="submit" value="Search" class="form-control btn btn-primary search">
                    </div>
                </div>
            </div>
            <div class="container mb-2 table-responsive" id="table-data">
                @include('backend.member.table')
            </div>
         </div>
     </div>
 </div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function (argument) {
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN' : $('meta[keyword="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.search' ,function (argument) {
            let role_id = $('.role_id').val();
            let permission_id = $('.permission_id').val();
            let keyword = $('.keyword').val();

            getUsers(role_id, permission_id, keyword, 1)
        })

        $(document).on('click', '.pagination a', function(event){
          if ($('.card-footer').hasClass('ajax')) {
            event.preventDefault(); 
            let page = $(this).attr('href').split('page=')[1];
            let role_id = $('.filter').data('role-id')
            let permission_id = $('.filter').data('permission-id')
            let keyword = $('.filter').data('keyword')
            getUsers(role_id, permission_id, keyword, page)
          }
        })

        function getUsers(role_id, permission_id, keyword, page){
          let url="member?page="+page;
            $.ajax({
              type:'GET',
              url: url,
              data: {role_id: role_id, permission_id: permission_id, keyword: keyword} ,
              success: (data) => {
                $('#table-data').html(data)
                $('.card-footer').addClass('ajax');
              }
          });
        }
    })
  </script>
@endsection