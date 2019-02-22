@extends('layouts.admin')
@section('content')
    <div class="box box-primary">

        @if(isset($doctor))
            {!! Form::model($doctor, ['url' => route('doctors.update', ['id' => $doctor->id]), 'method' => 'PUT']) !!}
        @else
            {!! Form::open(['url' => route('doctors.store')]) !!}
        @endif


        <div class="box-body">
            <div class="row">
                <div class="form-group col-md-6">
                    {!! Form::label('name', trans('doctors.form.name'), ['class' => 'control-label']) !!}
                    {!! Form::text('name', null, ['class' => 'form-control', 'required' => true]) !!}
                </div>
                <div class="form-group col-md-6">
                    {!! Form::label('email', trans('doctors.form.email'), ['class' => 'control-label']) !!}
                    {!! Form::email('email', null, ['class' => 'form-control', 'required' => true]) !!}
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('password', trans('doctors.form.password'), ['class' => 'control-label']) !!}
                        {!! Form::password('password', ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('re_password', trans('doctors.form.re-password'), ['class' => 'control-label']) !!}
                        {!! Form::password('re_password', ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer">
            {!! Form::submit(isset($user) ? trans('doctors.btn.update') : trans('doctors.btn.submit'), ['class' => 'btn btn-primary']) !!}
            <a href="{{route('doctors.index')}}" class="btn btn-default">
                <i class="fa fa-angle-left"></i> {{trans('doctors.btn.cancel')}}
            </a>
        </div>
        {!! Form::close() !!}
    </div>
    <script type="text/javascript">
        $(function () {
            $('.role-option').attr('disabled', $('#full-access').is(':checked'));
            $('#full-access').click(function () {
                $('.role-option').attr('disabled', $(this).is(':checked'));
            });
        });
    </script>
@endsection