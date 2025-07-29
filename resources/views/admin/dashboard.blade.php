@extends('layouts.admin')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="mt-4">Dashboard</h1>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="card card-stats">
            <div class="card-body ">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Total Users</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $totalUsers }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card card-stats">
            <div class="card-body ">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Total Services</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $totalServices }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <h4>Recent Users</h4>
        <ul class="list-group">
            @foreach($recentUsers as $user)
            <li class="list-group-item">
                {{ $user->name }}
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
