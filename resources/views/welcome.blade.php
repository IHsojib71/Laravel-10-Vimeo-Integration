<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vimeo Video Uploader</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

    <div class="col-8 mx-auto mt-4">
        <div>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-success m-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Upload Video
            </button>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('upload.video') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Vimeo Uploader</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div>
                                    <label for="formFileLg" class="form-label">Video Title</label>
                                    <input class="form-control form-control-lg" name="title" type="text"
                                        placeholder="Video Title">
                                </div>
                                @error('title')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror

                                <div>
                                    <label for="formFileLg" class="form-label">Select Video</label>
                                    <input class="form-control form-control-lg" name="video" type="file">
                                </div>
                                @error('video')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @error('video')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <table class="table">
            <thead>
                <tr class="text-center">
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($videos as $video)
                    <tr class="text-center">
                        <th scope="row">{{ $loop->index + 1 }}</th>
                        <td>{{ $video->title }}</td>
                        <td class="d-flex">
                            <a href="{{ route('watch.video',$video->id) }}">

                            <button class="btn btn-info mx-2">Watch</button>
                            </a>

                            <a href="{{route('delete.video',$video->id)}}"><button class="btn btn-danger">Delete</button></a>
                        </td>

                    </tr>
                @empty
                    <tr class="text-center">
                        <td colspan="3">No Videos Uploaded!</td>
                    </tr>
                @endforelse


            </tbody>
        </table>

        <div>
            {{ $videos->links()  }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
