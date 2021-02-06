@extends('layouts.master')

@section('title', 'Товар')

@section('content')
        <h1>{{ $product->name }}</h1>
        <h2> {{ $product->category->name }}</h2>
        <p>Цена: <b>{{ $product->price }} ₽</b></p>
        <img src="{{ Storage::url($product->image) }}">
        <p>{{ $product->description }}</p>
        <form action="{{ route('basket-add', $product->id) }}" method="POST">
            <button type="submit" class="btn btn-primary" role="button">В корзину</button>
            @csrf
        </form>
@endsection
