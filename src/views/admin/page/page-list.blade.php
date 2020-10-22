@extends('adfm::admin.layout')

@section('content')
<section class="content">
    <div class="row">
        <div class="col-xs-12">

            <div class="pages-title" style="width: 97.4%; margin: 0 auto;">
                <h1>Содержимое</h1>
                <hr>
            </div>

            <div class="box">
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Заголовок</th>
                            <th>Дата создания</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($items as $item)
                            <tr>
                                <td><a href="{{route('adfm.pages.edit', $item->id)}}">{{$item->title}} {{$item->id}}</td>
                                <td>{{$item->created_at}}</td>
                                <td>{{$item->slug}}</td>
                            </tr>
                        @endforeach
{{--                        {% for page in pages %}--}}
{{--                        <tr>--}}
{{--                            <td style="max-width: 400px;"><a href="/admin/content/{{page.id}}" style="color: black; text-decoration: none;">{{page.title}} </a></td>--}}
{{--                            <td>{{page.created_at|date('Y-m-d H:i:s')}}</td>--}}
{{--                            <td><a target="_blank" href="/{{page.alias}}" style="color: black;">Перейти на страницу</a></td>--}}
{{--                        </tr>--}}
{{--                        {% endfor %}--}}
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>


            <a class="btn btn-primary" href="/pages/create">Добавить</a>

            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->

@endsection
