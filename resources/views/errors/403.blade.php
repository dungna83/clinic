@extends('layouts.admin')
@section('content')

    <div class="error-page">
        <h2 class="headline text-yellow"> 403</h2>
        <div class="error-content">
            <h3><i class="fa fa-warning text-yellow"></i> {{trans('auth.permission-denied')}}</h3>
            <p>
                {{ trans('auth.you-can-not-access-this-link') }}
            </p>
            <p>
                {{ trans('auth.ask-admin-for-permission') }}
            </p>
        </div><!-- /.error-content -->
    </div><!-- /.error-page -->
@endsection