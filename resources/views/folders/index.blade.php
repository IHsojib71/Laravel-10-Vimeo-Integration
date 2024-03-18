@extends('layouts.main')
@section('content')
    <div class="col-8 mx-auto mt-4">
        <div class="text-center">
            <h1>My Folders</h1>
        </div>
        <div class="d-flex justify-content-between">
            <a href="/"><button class="btn btn-danger">Back</button></a>
            <a href="{{route('folder.create')}}"><button class="btn btn-primary">Create New Folder</button></a>
        </div>

        <table class="table">
            <thead>
            <tr class="text-center">
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($folders as $folder)
                <tr class="text-center">
                    <th scope="row">{{ $loop->index + 1 }}</th>
                    <td>{{ $folder->title }}</td>
                    <td class="d-flex justify-content-center">
                        <a href="{{ route('folder.edit',$folder->id) }}"> <button class="btn btn-info mx-2">Edit</button>
                        </a>
                        <form action="{{route('folder.destroy',$folder->id)}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>

                    </td>

                </tr>
            @empty
                <tr class="text-center">
                    <td colspan="3">No Folders!</td>
                </tr>
            @endforelse


            </tbody>
        </table>

        <div>
            {{ $folders->links()  }}
        </div>
    </div>
@endsection
