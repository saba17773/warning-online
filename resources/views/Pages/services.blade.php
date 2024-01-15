@extends('layouts.app')

@section('content')
        {{$title}}

        @if (count($services) > 0)
            @foreach($services as $service)
            <li> <i class="fas fa-address-book"></i>{{$service}} </li>
            @endforeach

           <?php
            try {
                DB::connection()->getPdo();
            } catch (\Exception $e) {
                die("Could not connect to the database.  Please check your configuration. error:" . $e );
            }
            ?>

        @endif
@endsection
