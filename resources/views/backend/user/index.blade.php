 @extends('backend.backend_template')
 @section('content')
 <!-- <div class="container">
     <div class="row">
        <div class="col p-2">
            <ul class="nav nav-pills custom-nav-pills justify-content-start">
                <li class="nav-item mr-2 mr-md-0">
                    <a href="#" class="nav-link py-2 px-3 active filter" data-toggle="tab" data-keyword="all"><span class="d-none d-md-block">All</span></a>
                </li>
                <li class="nav-item" data-toggle="chart">
                    <a href="#" class="nav-link py-2 px-3 filter" data-toggle="tab" data-keyword="online"><span class="d-none d-md-block">Online</span></a>
                </li>
            </ul>
        </div>
     </div>
 </div> -->
 <div class="container">      
     <div class="row">
        @if (session('status'))
            <div class="alert alert-primary col-md-6 offset-3">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              {{ session('status') }}
            </div>
        @endif
         <div class="col-lg-12 p-3 card table-responsive" id="table-data">
                @include('backend.user.table')
         </div>
     </div>
 </div>
@endsection
@section('script')
<script>
    $('.filter').click(function () {
        let keyword = $(this).data('keyword');
        let url = `{{route('admin.user.index')}}`;
        console.log(keyword);
        $.ajax({
            type:'GET',
            url: url,
            data: {keyword: keyword},
            success: (data) => {
            $('#table-data').html(data)
            }
        }); 
    })
</script>
@endsection