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
                      <span class="heading">{{count($user->userInfo->creator->subscriptions)}}</span>
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
                  <i class="ni business_briefcase-24 mr-2"></i>{{$user->role->name}}, {{$user->userInfo->gender}}
                  <i class="ni business_briefcase-24 mr-2"></i><a href="{{$user->userInfo->profile_url}}" target="_blank">{{$user->userInfo->profile_url}}</a>
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

                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="phone_1">Region<i class="text-danger"></i></label>
                            @if($user->userInfo->region_id)
                              <input disabled type="text" id="input-last-name" class="form-control" placeholder="" value="{{$user->userInfo->region->name}}">
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label class="form-control-label" for="address">Address</label>
                            <textarea disabled id="address" rows="4" class="form-control @error('address') is-invalid @enderror" name="address" placeholder="Address">{{$user->userInfo->address}}</textarea>
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>


                    <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label class="form-control-label" for="bio">Bio</label>
                            <textarea disabled id="bio" rows="4" class="form-control @error('bio') is-invalid @enderror" name="bio" placeholder="Bio">{{$user->userInfo->bio}}</textarea>
                            @error('bio')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                  </div>
                  @endif
                </div>
                <hr class="my-4" />
                <!-- Address -->
                @if ($user->role_id != 1)
                <h6 class="heading-small text-muted mb-4">Social Links</h6>
                <div class="row">
                    @if($user->userInfo->socials != null)
                        @foreach($user->userInfo->socials as $social)
                            <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="social_name">Social:<i class="text-danger">*</i></label>
                                    <input disabled type="text" name="social_name[]" class="form-control @error('social_name') is-invalid @enderror" value="{{$social->name}}" autofocus id="name">
                                    @error('social_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="link">Link<i class="text-danger">*</i></label>
                                    <input disabled type="text" name="link[]" class="form-control @error('link') is-invalid @enderror" value="{{$social->link}}" autofocus id="email">
                                    @error('link')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                    @endif
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