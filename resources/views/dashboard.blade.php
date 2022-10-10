@extends('layouts.main')

@section('page_title','welcome')

@section('title', 'Inlokari - Dashboard')

@section('breadcrumb')

        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active">Dashboard</li>

@endsection

@section('content')

<p>We don't have enough data to show on Dashboard. Go to <a href="/jobs" style="text-decoration: underline">Job List</a> page to see the job list.</p>

@endsection

@push('custom-script')


@endpush
