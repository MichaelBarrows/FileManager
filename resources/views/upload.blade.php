@extends('layouts.layout')
@section('page_title')
Upload a file
@endsection
@section('content')
<form action="/upload" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="exampleInputEmail1">Select a file to upload:</label>
        <input type="file" name="file" id="file" class="form-control">
    </div>
    <br>
    <button type="submit" class="form-control btn btn-success">Upload</button>
</form>
@endsection
