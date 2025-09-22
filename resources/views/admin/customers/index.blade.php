@extends('layouts.admin')

@section('title', 'Products Management')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Customers</li>
@endsection

@section('content')
<h1>Customers</h1>
@endsection