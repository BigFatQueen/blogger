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
            <form method="post" action="{{ route('admin.content.update', \App\Helper\Crypt::crypt()->encrypt( $content->id ))}}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                        <div class="form-group">
                            <label class="form-control-label">Creator<i class="text-danger">*</i></label>
                            <input type="hidden" name="creator_id" value="{{$content->creator_id}}">
                            <select name="creator_id" id="" class="form-control creator_id" disabled>
                                <option value="">Choose One Creator</option>
                                @foreach ($creators as $creator)
                                    <option value="{{$creator->id}}" {{ ($creator->id == $content->creator_id) ? 'selected' : '' }}>{{$creator->user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                        <div class="form-group">
                            <label class="form-control-label">Category<i class="text-danger">*</i></label>
                            <select name="category_id" id="" class="form-control category_id">
                                <option value="">Choose One Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{$category->id}}" {{ ($category->id == $content->category_id) ? 'selected' : '' }}>{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                        <div class="form-group custom-control custom-control-alternative custom-checkbox">
                            <label for="permission">Subscription Plans<i class="text-danger">*</i></label>   
                                <div class="row">
                                    @foreach($content->creator->subscriptionPlans as $subscription_plan)
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            <input class="custom-control-input" id="subscription_plan-{{$subscription_plan->id}}" type="checkbox" name="subscription_plan[]" value="{{$subscription_plan->id}}"
                                            @php
                                                if (in_array($subscription_plan->id, $content_subscription_plans))
                                                echo "checked";
                                            @endphp>
                                            <label class="custom-control-label" for="subscription_plan-{{$subscription_plan->id}}">{{$subscription_plan->level}} &nbsp;&nbsp;&nbsp;</span> </label>
                                        </div>
                                    @endforeach
                                </div>
                        </div>
                    </div>
                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                        <div class="form-group">
                            <label class="form-control-label" for="title">Title<i class="text-danger">*</i></label>
                            <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" placeholder="Enter Title" value="{{$content->title}}">
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                        <div class="form-group">
                            <label class="form-control-label" for="content">Content</label>
                            <textarea id="content" rows="4" class="form-control @error('content') is-invalid @enderror" name="content" placeholder="A few words about you ...">{{$content->content}}</textarea>
                            @error('content')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-12">
                      <div class="form-group">
                        <label class="form-control-label" for="input-first-name">Image</label>
                        <div class="row">
                          <div class="col-12">
                            
                          @php $images = json_decode($content->image) @endphp
                            @if($images != null)
                            <input type="hidden" name="image" value="{{$content->image}}">
                            <div class="form-group row" id="banner_image_row">
                                @foreach ($images as $image)
                                    <div class="col-xs-6 col-sm-6 col-md-4 col-lg-3 mb-2">
                                        <img class="show_banner_image" width="230" height="150"
                                        src="{{ asset('/storage/'.$image) }}">
                                    </div>
                                @endforeach
                            </div>
                            @endif
                            <input id="input_banner_image" type="file" accept="image/*" name="image[]"
                                onchange="loadBannerImage(event)" multiple>
                                
                                @if (session('images_errors'))
                                    <ul>
                                        @foreach (session('images_errors') as $error)
                                            <li class="text-danger">
                                                <strong>{{ $error }}</strong>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            <div id="show_banner" class="form-group row">
                            </div>
                            </div>
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                      <div class="form-group">
                        <label class="form-control-label" for="input-first-name">Audio</label>
                        <div class="row">
                          <div class="col-12">
                            @if ($content->audio)
                            <input type="hidden" name="audio" value="{{$content->audio}}">
                            <audio controls>
                              <source src="{{asset('/storage/'. $content->audio) }}" type="audio/mpeg">
                              Your browser does not support the audio element.
                            </audio>
                            @endif
                            <input id="audio" type="file" class="form-control @error('audio') is-invalid @enderror" name="audio" placeholder="Enter audio" value="{{$content->audio}}">
                            @error('audio')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                      <div class="form-group">
                        <label class="form-control-label" for="input-last-name">Video</label>
                        <div class="row">
                          <div class="col-12">
                            @if ($content->video)
                            <input type="hidden" name="video" value="{{$content->video}}">
                            <video height="240" controls>
                              <source src="{{asset('/storage/'. $content->video) }}" type="video/mp4">
                              Your browser does not support the video tag.
                            </video>
                            @endif
                            <input id="video" type="file" class="form-control @error('video') is-invalid @enderror" name="video" placeholder="Enter video" value="{{$content->video}}">
                            @error('video')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                      <div class="form-group">
                        <label class="form-control-label" for="input-address">Link</label>
                        <div class="row">
                          <div class="col 12">
                            <a href="{{$content->link}}" target="_blank" rel="noopener noreferrer">{{$content->link}}</a>
                            <input id="link" type="text" class="form-control @error('link') is-invalid @enderror" name="link" placeholder="Enter Link" value="{{$content->link}}">
                            @error('link')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                      <div class="form-group">
                        <label class="form-control-label" for="input-address">Embed URL</label>
                        <div class="row">
                          <div class="col-12">
                            @if ($content->embed_url)
                            <!-- {!! $content->embed_url !!} -->
                            <iframe  src="https://www.youtube.com/embed/{{$content->embed_url}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>   
                            @endif
                            <input id="embed_url" type="text" class="form-control @error('embed_url') is-invalid @enderror" name="embed_url" placeholder="Enter embed_url" value="{{$content->embed_url}}">
                            @error('embed_url')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
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
@section('script')

<script>
    function loadBannerImage(event) {
        let images = document.getElementsByClassName('banner_image');
        while(images.length > 0){
            images[0].removeAttribute('src');
            images[0].parentNode.removeChild(images[0]);
        }
        let show_images = document.getElementsByClassName('show_banner_image');
        while(show_images.length > 0){
            show_images[0].removeAttribute('src');
            show_images[0].parentNode.removeChild(show_images[0]);
        }
        var image = document.getElementById('input_banner_image');
        let imageFiles = event.target.files;
        document.getElementById('show_banner').style.display = "";
        for (let index = 0; index < imageFiles.length; index++) {
            const element = imageFiles[index];
            let img = document.createElement("IMG");
            img.setAttribute("src", URL.createObjectURL(element));
            img.setAttribute("width", "259");
            img.setAttribute("class", "d-block m-3 banner_image");
            img.setAttribute("style", "padding: 6px;");
            document.getElementById("show_banner").appendChild(img);
        }
    }

    function removeBannerImage()
    {
        let images = document.getElementsByClassName('banner_image');
        while(images.length > 0){
            images[0].removeAttribute('src');
            images[0].parentNode.removeChild(images[0]);
        }
        document.getElementById('input_banner_image').value= null;
        document.getElementById('old_banner_image').value= null;
        document.getElementById('banner_image_row').style.display = "none";
        document.getElementById('show_banner').style.display = "none";
    }

</script>

@endsection