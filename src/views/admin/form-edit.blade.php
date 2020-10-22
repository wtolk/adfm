@extends('adfm::admin.layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col col-xs-12">

                <div class="pages-title">
                    <h1>{{$form->title}}</h1>
                    <hr>
                </div>

                <div class="box">

                    <div class="box-body">
                        @if($form->isModelExists)
                            <form method="POST" id="main-form" action="{{$form->route}}" role="form" enctype="multipart/form-data" autocomplete="off">
                            @method('PATCH')
                        @else
                            <form method="POST" id="main-form" action="{{$form->route}}" role="form" enctype="multipart/form-data" autocomplete="off">
                        @endif
                            @csrf
                            <div class="row">

                                @foreach($form->columns as $column)
                                <div class="{{$column->class}}"> <!-- Первая панель -->
                                    <div class="form">
                                        @foreach($column->renderedFields as $field)
                                            {!! $field !!}
                                        @endforeach

                                    </div>
                                </div>
                                @endforeach
                            </div>
                            </form>

                            <div class="row">
                                @foreach($form->renderedButtons as $button)
                                    {!! $button !!}
                                @endforeach
{{--                                <button type="submit" form="main-form" class="button">Сохранить</button>--}}
{{--                                @if($item->exists)--}}
{{--                                    <form action="{{route('adfm.pages.destroy', $item->id)}}" method="POST">--}}
{{--                                        @method('DELETE')--}}
{{--                                        @csrf--}}
{{--                                        <button type="submit">Удалить страницу</button>--}}
{{--                                    </form>--}}
{{--                                    <a href="/api/page/{{ $item->id }}/clone">--}}
{{--                                        <button type="button" class="button">Клонировать страницу</button>--}}
{{--                                    </a>--}}
{{--                                <a href="/admin/menus/links/add/Page/{{$item->id}}">--}}
{{--                                    <button type="button" class="button">Добавить в меню</button>--}}
{{--                                </a>--}}
{{--                                @endif--}}
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
