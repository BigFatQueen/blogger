@extends('backend.backend_template')
@section('content')
    <div class="container-fluid mt--4">
      <div class="row">
        <div class="col-xl-4 order-xl-2">
          <div class="card card-profile">
            @if ($user->role_id != 1)
            <img src="{{ asset('/storage/'.$user->userInfo->cover_photo) }}" alt="Image placeholder" class="card-img-top">
            <div class="row justify-content-center">
              <div class="col-lg-3 order-lg-2">
                <div class="card-profile-image">
                  <a href="#">
                    <img src="{{ asset('/storage/'.$user->userInfo->profile_image) }}" class="rounded-circle">
                  </a>
                </div>
              </div>
            </div>
            @endif
            <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4 mt-4">
              <div class="d-flex justify-content-between">
                <a href="#" class="btn btn-sm btn-info  mr-4 ">@if(Cache::has('active-' . $user->id)) Online @else  Not Online @endif</a>
                @php $newDateTime = date('d-m-Y h:i A', strtotime($user->last_seen)); @endphp  
                <a href="#" class="btn btn-sm btn-default float-right">@if(isset($user->last_seen)) {{ $newDateTime }} @else New User @endif</a>
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
                  {{$user->name}}<span class="font-weight-light">, @if ($user->role_id != 1) {{$user->userInfo->phone_no}} @endif</span>
                </h5>
                <div class="h5 mt-4">
                  <i class="ni business_briefcase-24 mr-2"></i>{{$user->role->name}}
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
                <h6 class="heading-small text-muted mb-4">User information</h6>
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-username">Username</label>
                        <input disabled type="text" id="input-username" class="form-control" placeholder="" value="{{$user->name}}">
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-email">Email address</label>
                        <input disabled type="email" id="input-email" class="form-control" value="{{$user->email}}">
                      </div>
                    </div>
                  </div>
                  @if ($user->role_id != 1)
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-first-name">Phone No</label>
                        <input disabled type="text" id="input-first-name" class="form-control" placeholder="" value="{{$user->userInfo->phone_no}}">
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-last-name">DOB</label>
                        <input disabled type="text" id="input-last-name" class="form-control" placeholder="" value="{{$user->userInfo->dob}}">
                      </div>
                    </div>
                  </div>
                  @endif
                </div>
                <hr class="my-4" />
                <!-- Address -->
                @if ($user->role_id != 1)
                <h6 class="heading-small text-muted mb-4">Other information</h6>
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="form-control-label" for="input-address">Embed URL</label>
                        <div class="row">
                          <div class="col 12">
                            <iframe  src="{{$user->userInfo->embed_url}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <hr class="my-4" />
                @endif
                <!-- Description -->
                @if($user->role_id == 2) 
                <h6 class="heading-small text-muted mb-4">About me</h6>
                <div class="pl-lg-4">
                  <div class="form-group">
                    <label class="form-control-label">About Me</label>
                    <textarea disabled rows="4" class="form-control" placeholder="">{{$user->userInfo->creator->description}}</textarea>
                  </div>
                </div>
                @endif
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection