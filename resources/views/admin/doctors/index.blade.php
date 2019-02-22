@extends('layouts.admin')
@section('content')
    <div style="padding-bottom: 10px;">
        <a href="{{route('doctors.create')}}" class="btn btn-success">
            <i class="fa fa-plus"></i> {{trans('doctors.btn.add')}}
        </a>
    </div>

    <div class="box">
        <div class="box-body">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 10px">#</th>
                    <th>{{trans('doctors.col.name')}}</th>
                    <th>{{trans('doctors.col.email')}}</th>
                    <th>{{trans('doctors.col.created_at')}}</th>
                    <th>{{trans('doctors.col.updated_at')}}</th>
                    <th>{{trans('doctors.col.deleted_at')}}</th>
                </tr>
                @if(!empty($listDoctor))
                    @foreach($listDoctor as $index => $item)
                        <tr>
                            <td>{{$index+1}}</td>
                            <td>
                                {{$item->name}}
                                <a href="{{route('doctors.edit', ['id' => $item->id])}}"><i class="fa fa-edit"></i></a>
                            </td>
                            <td>{{$item->email}}</td>
                            <td>{{$item->created_at}}</td>
                            <td>{{$item->updated_at}}</td>
                            <td>
                                <a style="cursor: pointer; {{!$item->trashed() ? 'color: red;' : ''}}"
                                   dir="delete-form-{{$item->id}}" class="remove-item"><i
                                            class="fa {{$item->trashed() ? 'fa-refresh' : 'fa-trash'}}"></i></a>
                                {{$item->deleted_at}}

                                {!! Form::open(['method' => 'DELETE','id' => 'delete-form-' . $item->id,'url' => route('doctors.destroy', ['id' => $item->id])]) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
    </div>
    <script type="text/javascript">
        $(function () {
            $('.remove-item').click(function () {
                if (confirm('{{trans('doctors.confirm')}}')) {
                    $('#' + $(this).attr('dir')).submit();
                }
            });
        });
    </script>
@endsection