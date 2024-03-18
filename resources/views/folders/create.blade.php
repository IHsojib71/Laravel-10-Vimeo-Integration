@extends('layouts.main')
@section('content')
    <div class="col-4 mx-auto mt-4">
        <div class="text-center">
            <h1>Create Folder</h1>
        </div>
    <form action="{{ route('folder.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
            <div>
                <div>
                    <label for="title" class="form-label">Folder Title</label>
                    <input class="form-control form-control-lg" name="title" type="text"
                           placeholder="Folder Title">
                </div>
                @error('title')
                <p class="text-danger">{{ $message }}</p>
                @enderror

            </div>
            <div class="mt-4">
                <a href="{{route('folder.index')}}"><button type="button" class="btn btn-danger">Cancel</button></a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>

    </form>
    </div>

@endsection
