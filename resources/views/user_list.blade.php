@extends('layouts.layout')

@section('page_title')
My Files
@endsection
@section('content')
    @if(isset($files))
        <div class="col">
            <div class="container file-list">
                <div class="row">
                    <div class="col heading">
                        <p>Filename</p>
                    </div>
                    <div class="col heading">
                        <p>Upload time</p>
                    </div>
                    <div class="col heading">
                        <p>Download link</p>
                    </div>
                    <!-- <div class="col heading">
                        <p>Delete</p>
                    </div> -->
                </div>
                @if($files->count() != 0)
                    @foreach($files as $file)
                        <div class="row">
                            <div class="col">
                                <p>{{ $file->name }}</p>
                            </div>
                            <div class="col">
                                <p>{{ $file->date() }}</p>
                            </div>
                            <div class="col">
                                <p><a class="btn btn-secondary form-control" role="button" href="/download/{{ $file->id }}">Download</a></p>
                            </div>
                            <!-- <div class="col">
                                <form method="POST" action="/delete/{{ $file->id }}">
                                    @csrf
                                    {{ method_field('DELETE') }}
                                    <input class="btn form-control btn-danger" type="submit" value="Delete">
                                </form>
                            </div> -->
                        </div>
                    @endforeach
                @else
                <div class="row">
                    <div class="col">
                        <p>You have no files, <a href="/upload">upload</a> one now?</p>
                    </div>
                </div>
                @endif
        </div>
    @endif
@endsection
