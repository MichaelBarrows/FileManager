@extends('layouts.layout')
@section('page_title')
All Files (admin)
@endsection
@section('content')
    @if(isset($all_files))
        <div class="col">
            <div class="container file-list">
                <div class="row">
                    <div class="col heading">
                        <p>Filename</p>
                    </div>
                    <div class="col heading">
                        <p>Upload time</p>
                    </div>
                    <div class="col-1 heading">
                        <p>Downloads</p>
                    </div>
                    <div class="col heading">
                        <p>Owner</p>
                    </div>
                    <div class="col heading">
                        <p>AWS filename</p>
                    </div>
                    <div class="col heading">
                        <p>Download</p>
                    </div>
                    <div class="col heading">
                        <p>Delete</p>
                    </div>
                </div>
                @if($all_files->count() != 0)
                    @foreach($all_files as $file)
                        <div class="row">
                            <div class="col">
                                <p>{{ $file->name }}</p>
                            </div>
                            <div class="col xlarge-2">
                                <p>{{ $file->date() }}</p>
                            </div>
                            <div class="col-1">
                                <p class="vertical-center">{{ $file->downloads }}</p>
                            </div>
                            <div class="col">
                                <p>{{ $file->user->name }}
                                @if(Auth::user()->id == $file->user->id)
                                    (you)
                                @endif</p>
                            </div>
                            <div class="col">
                                <p>{{ $file->id }}.{{ $file->extension }}</p>
                            </div>
                            <div class="col">
                                <p><a class="btn form-control btn-secondary" role="button" data-bs-toggle="button" href="/download/{{ $file->id }}">Download</a></p>
                            </div>
                            <div class="col">
                                <form method="POST" action="/delete/{{ $file->id }}">
                                    @csrf
                                    {{ method_field('DELETE') }}
                                    <input class="btn form-control btn-danger" type="submit" value="Delete">
                                </form>
                            </div>
                        </div>
                    @endforeach

                @else
                    <div class="row">
                        <div class="col">
                            <p>There are no files, <a href="/upload">upload</a> one now?</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
@endsection
