@extends('adfm::admin.layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col col-xs-12">

                <div class="pages-title">
                    <h1>Создание страницы</h1>
                    <hr>
                </div>

                <div class="box">

                    <div class="box-body">
                        @if($item->exists)
                            <form method="POST" id="main-form" action="{{route('adfm.pages.update', $item->id)}}" role="form" enctype="multipart/form-data" autocomplete="off">
                            @method('PATCH')
                        @else
                            <form method="POST" id="main-form" action="{{route('adfm.pages.store')}}" role="form" enctype="multipart/form-data" autocomplete="off">
                        @endif
                            @csrf
                            <div class="row">
                                <div class="col col-md-8"> <!-- Первая панель -->
                                    <div class="form">
                                        <div class="field">
                                            <label for="title">Заголовок</label>
                                            <input type="text" name="page[title]" id="title" class="form-control"
                                                   value="{{$item->title}}" placeholder="Введите название страницы"
                                                   required="required">
                                        </div>

                                        <div class="field">
                                            <div class="row">

                                                <div class="col col-xs-12">
                                                    <label for="">
                                                        <input type="hidden" name="page[settings][developer-mode]" value="0" />
                                                        <input type="checkbox" id="developer-mode" name="page[settings][developer-mode]">
                                                        Режим разработчика
                                                    </label>

                                                </div>
                                            </div>

                                            <textarea id="editor1" name="page[content]" class="form-control"
                                                      placeholder="Введите сюда свой текст страницы">{{ $item->content }}</textarea>
                                        </div>

                                        <div class="field">
                                            <label>Прикрепить файлы</label>
                                            <div class="page-files"></div>
                                        </div>


                                    </div>
                                </div> <!-- /Первая панель -->

                                <div class="col col-md-4"> <!-- Вторая панель -->
                                    <div class="form">
                                        <div class="field">
                                            <label for="title">TITLE (мета-тег)</label>
                                            <input type="text" name="page[meta][title]" id="meta_title" class="form-control"
                                                   value="{{$item->meta['title']}}" placeholder="Введите название страницы">
                                        </div>
                                        <div class="field">
                                            <label for="slug">description (мета-тег)</label>
                                            <textarea id="editor2" name="page[meta][description]" class="form-control">{{$item->meta['description']}}</textarea>
                                        </div>

                                        <div class="field">
                                            <label for="main_page">Сделать эту страницу главной: </label>
                                            <input type="checkbox" id="main_page" name="main_page">
                                        </div>
                                        <div class="field">
                                            <label for="slug">Синоним</label>
                                            <input type="text" name="page[slug]" id="alias" class="form-control"
                                                   value="{{$item->slug}}" placeholder="Cиноним страницы">
                                        </div>

                                    </div>
                                </div> <!-- /Вторая панель -->
                            </div>
                            </form>

                            <div class="row">
                                <button type="submit" form="main-form" class="button">Сохранить</button>
                                @if($item->exists)
                                    <form action="{{route('adfm.pages.destroy', $item->id)}}" method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit">Удалить страницу</button>
                                    </form>
                                    <a href="/api/page/{{ $item->id }}/clone">
                                        <button type="button" class="button">Клонировать страницу</button>
                                    </a>
                                <a href="/admin/menus/links/add/Page/{{$item->id}}">
                                    <button type="button" class="button">Добавить в меню</button>
                                </a>
                                @endif
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
