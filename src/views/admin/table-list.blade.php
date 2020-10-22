@extends('adfm::admin.layout')

@section('content')
<section class="content">
    <div class="row">
        <div class="col-xs-12">

            <div class="pages-title">
                <h1>Содержимое</h1>
                <hr>
            </div>

            <div class="box">
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                @foreach($form->fields as $field)
                                <th>{{$field->title}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                        @foreach(reset($form->source) as $item)
                            <tr>
                                @foreach($form->fields as $field)
                                    <td>

                                        @if ($field->link)
                                            {{($field->link)($item)}}
                                        @else
                                            {{ $item->{$field->field_name} }}
                                        @endif
                                    </td>

                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>


        @foreach($form->renderedButtons as $button)
            {!! $button !!}
        @endforeach

            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->

@endsection
