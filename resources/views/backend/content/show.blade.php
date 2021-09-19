@extends('backend.backend_template')
@section('content')
    <div class="container-fluid mt--4">
      <div class="row">
        <div class="col-xl-4 order-xl-2">
          <div class="card card-profile">
            @if ($content->creator->userInfo->user->role_id != 1)
            <img src="{{ asset('/storage/'.$content->creator->userInfo->cover_photo) }}" alt="Image placeholder" class="card-img-top">
            <div class="row justify-content-center">
              <div class="col-lg-3 order-lg-2">
                <div class="card-profile-image">
                  <a href="#">
                    <img src="{{ asset('/storage/'.$content->creator->userInfo->profile_image) }}" class="rounded-circle">
                  </a>
                </div>
              </div>
            </div>
            @endif
            <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4 mt-4">
              <div class="d-flex justify-content-between">
                <a href="#" class="btn btn-sm btn-info  mr-4 ">@if(Cache::has('active-' . $content->creator->userInfo->user->id)) Online @else  Not Online @endif</a>
                @php $newDateTime = date('d-m-Y h:i A', strtotime($content->creator->userInfo->user->last_seen)); @endphp  
                <a href="#" class="btn btn-sm btn-default float-right">@if(isset($content->creator->userInfo->user->last_seen)) {{ $newDateTime }} @else New User @endif</a>
              </div>
            </div>
            <div class="card-body pt-0">
              <div class="row">
                <div class="col">
                  <div class="card-profile-stats d-flex justify-content-center">
                    <div>
                      <span class="heading">22</span>
                      <span class="description">Subscriptions</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="text-center">
                <h5 class="h3">
                  {{$content->creator->userInfo->user->name}}<span class="font-weight-light">, @if ($content->creator->userInfo->user->role_id != 1) {{$content->creator->userInfo->user->userInfo->phone_no}} @endif</span>
                </h5>
                <div class="h5 mt-4">
                  <i class="ni business_briefcase-24 mr-2"></i>{{$content->creator->userInfo->user->role->name}}
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-8 order-xl-1">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-8">
                  <h3 class="mb-0">Profile </h3>
                </div>
                <div class="col-4 text-right">
                  <a href="{{URL::previous()}}" class="btn btn-sm btn-primary"><i class="fas fa-backward">  Back</i></a>
                </div>
              </div>
            </div>
            <div class="card-body">
              <form>
                <h6 class="heading-small text-muted mb-4">Content information</h6>
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-username">Title</label>
                        <input disabled type="text" id="input-username" class="form-control" placeholder="" value="{{$content->title}}">
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-username">Category</label>
                        <input disabled type="text" id="input-username" class="form-control" placeholder="" value="{{$content->category->name}}">
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-group">
                        <label class="form-control-label" for="input-email">Content</label>
                        <textarea disabled rows="4" class="form-control" placeholder="">{{$content->content}}</textarea>
                      </div>
                    </div>
                  </div>
                  <hr class="my-4" />
                  <!-- Address -->
                  <h6 class="heading-small text-muted mb-4">Media information</h6>
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label class="form-control-label" for="input-first-name">Image</label>
                        <div class="row">
                          <div class="col 12">
                            
                          @php $images = json_decode($content->image); @endphp
                          @if($images != null)
                            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                <ol class="carousel-indicators">
                                  @foreach ($images as $key => $image)
                                    <li data-target="#carouselExampleIndicators" data-slide-to="{{$key}}" class="{{ ($key == 0 ) ? 'active' : '' }}"></li>
                                  @endforeach
                                </ol>
                                <div class="carousel-inner">
                                @foreach ($images as $key => $image)
                                  <div class="carousel-item {{ ($key == 0 ) ? 'active' : '' }}">
                                    <img class="d-block w-100" src="{{asset('/storage/'. $image) }}" alt="First slide">
                                  </div>
                                  @endforeach
                                </div>
                                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                  <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                  <span class="sr-only">Next</span>
                                </a>
                              </div>
                            </div>
                          @endif
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label class="form-control-label" for="input-first-name">Audio</label>
                        <div class="row">
                          <div class="col-12">
                            <audio controls>
                              <source src="{{asset('/storage/'. $content->audio) }}" type="audio/mpeg">
                              Your browser does not support the audio element.
                            </audio>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label class="form-control-label" for="input-last-name">Video</label>
                        <div class="row">
                          <div class="col-12">
                            <video height="240" controls>
                              <source src="{{asset('/storage/'. $content->video) }}" type="video/mp4">
                              Your browser does not support the video tag.
                            </video>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <hr class="my-4" />
                <!-- Address -->
                <h6 class="heading-small text-muted mb-4">Other information</h6>
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="form-control-label" for="input-address">Link</label>
                        <div class="row">
                          <div class="col 12">
                            <a href="{{$content->embed_url}}" target="_blank" rel="noopener noreferrer">{{$content->embed_url}}</a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="form-control-label" for="input-address">Embed URL</label>
                        <div class="row">
                          <div class="col 12">
                            <iframe  src="{{$content->embed_url}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <hr class="my-4" />
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection