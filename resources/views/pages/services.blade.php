@extends('layouts.master')

@section('content')
    <h1>{{$sTitle}}</h1>
    @if(count($aServices) > 0)
        <ul class="list-group">
            @foreach($aServices as $sService)
                <li class="list-group-item">{{$sService}}</li>
            @endforeach
        </ul>
    @endif
@endsection