@extends('adfm::public.layout')
@section('meta-title', $page->title)
@section('content')
    <h1>{{$page->title}}</h1>
    <div class="page-content">
        {!! $page->content !!}
        @if(count($page->files) > 0)
            <div class="files">
                <h4 class="h3">Прикрепленные файлы</h4>
                <ul>
                    @foreach($page->files as $file)
                        <li><a href="{!! $file->url !!}">{{$file->original_name}}</a></li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endsection
