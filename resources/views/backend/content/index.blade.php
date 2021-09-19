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
                    <div class="col-12 col-xs-12 col-sm-6 com-md-4 col-lg-3 col-xl-3 mb-2">
                        <select name="creator_id" id="" class="form-control creator_id">
                            <option value="">Choose One Creator</option>
                            @foreach ($creators as $creator)
                                <option value="{{$creator->id}}">{{$creator->userInfo->user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-xs-12 col-sm-6 com-md-4 col-lg-3 col-xl-3 mb-2">
                        <select name="category_id" id="" class="form-control category_id">
                            <option value="">Choose One Category</option>
                            @foreach ($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-xs-12 col-sm-6 com-md-4 col-lg-3 col-xl-3 mb-2">
                        <input type="text" name="keyword" placeholder="Search Keyword..." class="form-control keyword">
                    </div>
                    <div class="col-12 col-xs-12 col-sm-6 com-md-4 col-lg-3 col-xl-3">
                        <input type="submit" value="Search" class="form-control btn btn-primary search">
                    </div>
                </div>
            </div>
            <div class="container mb-2 table-responsive" id="table-data">
                @include('backend.content.table')
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
            let creator_id = $('.creator_id').val();
            let category_id = $('.category_id').val();
            let keyword = $('.keyword').val();

            getUsers(creator_id, category_id, keyword, 1)
        })

        $(document).on('click', '.pagination a', function(event){
          if ($('.card-footer').hasClass('ajax')) {
            event.preventDefault(); 
            let page = $(this).attr('href').split('page=')[1];
            let creator_id = $('.filter').data('creator-id')
            let category_id = $('.filter').data('category-id')
            let keyword = $('.filter').data('keyword')
            getUsers(creator_id, category_id, keyword, page)
          }
        })

        function getUsers(creator_id, category_id, keyword, page){
          let url="content?page="+page;
            $.ajax({
              type:'GET',
              url: url,
              data: {creator_id: creator_id, category_id: category_id, keyword: keyword} ,
              success: (data) => {
                $('#table-data').html(data)
                $('.card-footer').addClass('ajax');
              }
          });
        }
    })
  </script>
@endsection