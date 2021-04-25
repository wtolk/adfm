@extends('adfm::public.layout')
@section('meta-title', $page->title)

@section('content')
    <h1>{{$page->title}}</h1>
    <div>{!! $page->content !!}</div>
@endsection
