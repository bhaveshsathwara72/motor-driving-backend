@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('About Us') }}</div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if(isset($aboutUs))
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    @if($aboutUs->about_image_1)
                                        <img src="{{ $aboutUs->about_image_1 }}" alt="About Us Image 1" class="img-fluid rounded mb-2">
                                    @endif
                                    <form action="{{ route('about.us.upload.image') }}" method="POST" enctype="multipart/form-data" class="mt-2">
                                        @csrf
                                        <input type="hidden" name="image_type" value="about_image_1">
                                        <div class="input-group">
                                            <input type="file" name="image" class="form-control" accept="image/*">
                                            <button type="submit" class="btn btn-primary">Upload Image 1</button>
                                        </div>
                                    </form>
                                </div>
                                
                                <div class="mb-3">
                                    @if($aboutUs->about_image_2)
                                        <img src="{{ $aboutUs->about_image_2 }}" alt="About Us Image 2" class="img-fluid rounded mb-2">
                                    @endif
                                    <form action="{{ route('about.us.upload.image') }}" method="POST" enctype="multipart/form-data" class="mt-2">
                                        @csrf
                                        <input type="hidden" name="image_type" value="about_image_2">
                                        <div class="input-group">
                                            <input type="file" name="image" class="form-control" accept="image/*">
                                            <button type="submit" class="btn btn-primary">Upload Image 2</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4 class="mb-3">Contact Information</h4>
                                <p><strong>Phone 1:</strong> {{ $aboutUs->about_phone_no_1 }}</p>
                                @if($aboutUs->about_phone_no_2)
                                    <p><strong>Phone 2:</strong> {{ $aboutUs->about_phone_no_2 }}</p>
                                @endif
                                <h4 class="mb-3 mt-4">Address</h4>
                                <p>{{ $aboutUs->address }}</p>
                                
                                <h4 class="mb-3 mt-4">Opening Hours</h4>
                                <p>{{ $aboutUs->opening_hours }}</p>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            No information available at the moment. Please check back later.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection