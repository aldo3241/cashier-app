@extends('layouts.app')

@section('title', 'Access Denied')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="empty">
                    <div class="empty-img">
                        <i class="fas fa-ban fa-5x text-danger"></i>
                    </div>
                    <p class="empty-title">Access Denied</p>
                    <p class="empty-subtitle text-muted">
                        You don't have permission to access this page.
                    </p>
                    <div class="empty-action">
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>Go to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
