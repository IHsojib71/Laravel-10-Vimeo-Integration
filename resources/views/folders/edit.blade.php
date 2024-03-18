@extends('layouts.main')
@section('content')
    <div class="col-4 mx-auto mt-4">
        <div class="text-center">
            <h1>Edit Folder</h1>
        </div>
        <form action="{{ route('folder.update', $folder->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div>
                <div>
                    <label for="title" class="form-label">Folder Title</label>
                    <input class="form-control form-control-lg" name="title" type="text" value="{{ old('title') ?? $folder->title  }}"
                           placeholder="Folder Title">
                </div>
                @error('title')
                <p class="text-danger">{{ $message }}</p>
                @enderror

            </div>
            <div class="mt-4">
                <a href="{{route('folder.index')}}"><button type="button" class="btn btn-danger">Cancel</button></a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>

@endsection
