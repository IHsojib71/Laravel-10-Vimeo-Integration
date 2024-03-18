@extends('layouts.main')
@section('content')

    <div class="col-8 mx-auto mt-4">
        <a href="/">
            <button class="btn btn-danger">Back</button></a>
        <iframe src="{{ 'https://player.vimeo.com/video/' . $video->video_id }}" style="width: 100%" height="550"
            frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
    </div>
@endsection
