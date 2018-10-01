@extends('layouts.master')

@section('content')
    {{--<div class="modal fade" id="admin-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
    <div id="admin-modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Dashboard Admins</h5>
                </div>
                <div class="modal-body">
                    <div class="admin-modal-panel">
                        <div class="admin-panel-body">
                            <div class="container modal-body-list">
                                <div class="row sk-fading-circle circle-admin-list">
                                    {{--@include('spinner')--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{--<button type="button" class="btn btn-secondary admin-btn-close" data-dismiss="modal">Close</button>--}}
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="{{asset('/js/admin.js')}}"></script>
@stop

@section('css')
    <link rel="stylesheet" href="{{asset('/css/_admin.css')}}">
@stop