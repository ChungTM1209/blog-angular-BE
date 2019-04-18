@extends('home')
@section('main')
                        <div class="row">
                            @if(count($posts)=== 0)
                                <p class="text-danger">Chưa có bài viết nào</p>
                            @else
                                @foreach($posts as $key => $post)




                                    <div class="col-md-6" style="margin-bottom: 20px">
                                        <div class="card">
                                            <!-- Card image -->
                                            <div class="view overlay">
                                                <a href="{{ route('post.show', $post->id) }}">
                                                    <img class="card-img-top"
                                                         src="{{asset('storage/'.$post->image)}}"
                                                         alt="Card image cap" style="width: 100%; height: 500px">
                                                </a>

                                                <a>
                                                    <div class="mask rgba-white-slight"></div>
                                                </a>
                                            </div>

                                            <!-- Card content -->
                                            <div class="card-body">

                                                <!-- Social shares button -->
{{--                                                <a class="activator waves-effect waves-light mr-4"><i--}}
{{--                                                            class="fas fa-share-alt"></i></a>--}}
                                                <!-- Title -->
                                                <a href="{{ route('post.show', $post->id) }}">
                                                    <h4 class="card-title">{{ $post->title }}</h4></b>
                                                </a>
                                                <hr>
                                                <!-- Text -->
                                                <p class="card-text">{{ $post->description }}</p>
                                                <!-- Link -->
{{--                                                <a href="{{ route('post.show', $post->id) }}"--}}
{{--                                                   class="black-text d-flex justify-content-end"><h5>Read more--}}
{{--                                                        <i class="fas fa-angle-double-right"></i></h5></a>--}}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
@endsection
