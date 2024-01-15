@extends('layouts.app')

@section('content')

    <h1>{{$post->title}}</h1>
    <div class="well">
        {{$post->body}}
    </div>
    <a href="/posts" class="btn btn-success"> Go Back </a>
    <hr>
    <small>Created on {{$post->created_at}}</small>
    <hr>
     
    <a href="/posts/{{$post->id}}/edit" class="btn btn-primary "> Edit </a>

    
    {{-- Delete Button --}}
    {!!Form::open(['action'=>['PostsController@destroy',$post->id],'method'=>'POST','class'=>'float-right','onsubmit'=>'return confirm("Are you sure you want to delete this post?")'])!!}
    {{Form::hidden('_method', 'DELETE')}}
    {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
    {!! Form::close() !!}
    

   



@endsection