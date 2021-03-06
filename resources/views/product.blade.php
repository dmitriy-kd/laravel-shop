@extends('layouts.master')

@section('title', 'Товар')

@section('content')
    <h1>{{ $product->name }}</h1>
    <h2> {{ $product->category->name }}</h2>
    <p>Цена: <b>{{ $product->price }} {{ \App\Services\CurrencyConversion::getCurrencySymbol() }}</b></p>
    <img src="{{ Storage::url($product->image) }}">
    <p>{{ $product->__('description') }}</p>
    @if ($product->isAvailable())
        <form action="{{ route('basket-add', $product->id) }}" method="POST">
            <button type="submit" class="btn btn-success" role="button">В корзину</button>
            @csrf
        </form>
    @else
        {{--            <input type="button" class="btn btn-default" value="Нет в наличии">--}}
        <p>Не доступен</p>
        <p>Сообщить о поступлении</p>
        @include('auth.layouts.error', ['fieldName' => 'email'])
        <form action="{{ route('subscription', $product) }}" method="POST">
            <input type="text" name="email">
            <button type="submit">Подписаться</button>
            @csrf
        </form>
    @endif
@endsection
