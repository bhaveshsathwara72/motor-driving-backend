@extends('adminlte::page')

@section('title', 'Admin Dashboard')

@section('content_header')
    <h1>Admin Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Welcome to Admin Panel</h3>
                </div>
                <div class="card-body">
                    <p>You are logged in as an administrator!</p>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Add any additional CSS here --}}
@stop

@section('js')
    <script>
        console.log('Admin dashboard loaded!');
    </script>
@stop