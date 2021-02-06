@extends('layouts.master')

@section('title', 'Категория:' . $category->name)

@section('content')
        <h1>
            {{ $category->name }}
        {{--@if ($code == 'mobiles')
            Мобильные телефоны
            @elseif ($code == 'portable')
            Портативная техника
            @elseif ($code == 'technics')
            Бытовая техника
        @endif--}}
        </h1>
        <p>Количество товаров в данной категории: {{ $category->products->count() }}</p>
        <p>
            {{ $category->description }}
        </p>
        <div class="row">
            @foreach($category->products as $product)
                @include('layouts.card', compact('product'))
            @endforeach
        </div>
@endsection
