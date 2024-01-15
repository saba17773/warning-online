@extends('layouts.app')

@section('content')

    <h1>Edit Post</h1>

    {!! Form::open(['action' => ['PostsController@update', $post->id], 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::Label('title', 'Title')}}
            {{Form::text('title', $post->title, ['Class' => 'form-control', 'Placeholder' => 'Title'])}}
        </div>
        <div class="form-group">
                {{Form::Label('body', 'Body')}}
                {{Form::textarea('body', $post->body, ['Class' => 'form-control', 'Placeholder' => 'Body Text'])}}
        </div>
        {{Form::hidden('_method', 'PUT')}}   {{-- Need this row to put method to HTML --}}
        {{Form::submit('Submit', ['Class' => 'btn btn-primary'])}}
    {!! Form::close() !!}

@endsection