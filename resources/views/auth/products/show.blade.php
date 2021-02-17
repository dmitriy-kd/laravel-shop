@extends('auth.layouts.master')

@section('title', $product->name)

@section('content')
    <div class="col-md-12">
        <h1>{{ $product->name }}</h1>
        <table class="table">
            <tbody>
            <tr>
                <th>
                    Поле
                </th>
                <th>
                    Значение
                </th>
            </tr>
            <tr>
                <td>ID</td>
                <td>{{ $product->id }}</td>
            </tr>
            <tr>
                <td>Код</td>
                <td>{{ $product->code }}</td>
            </tr>
            <tr>
                <td>Название</td>
                <td>{{ $product->name }}</td>
            </tr>
            {{--<tr>
                <td>Название en</td>
                <td>{{ $category->name_en }}</td>
            </tr>--}}
            <tr>
                <td>Описание</td>
                <td>{{ $product->description }}</td>
            </tr>
            <tr>
                <td>Цена</td>
                <td>{{ $product->price }}</td>
            </tr>
            <tr>
                <td>Количество</td>
                <td>{{ $product->count }}</td>
            </tr>
            {{--<tr>
                <td>Описание en</td>
                <td>{{ $category->description_en }}</td>
            </tr>--}}
            <tr>
                <td>Категория</td>
                <td>{{ $product->category->name }}</td>
            </tr>
            <tr>
                <td>Изображение</td>
                <td><img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" height="240px"></td>
            </tr>
            <tr>
                <td>Лейблы</td>
                <td>
                    @if($product->isNew())
                        <span class="badge badge-success">Новинка</span>
                    @endif
                    @if($product->isRecommend())
                        <span class="badge badge-warning">Рекомендуемые</span>
                    @endif
                    @if($product->isHit())
                        <span class="badge badge-danger">Хит продаж</span>
                    @endif
                </td>
            </tr>
            </tbody>

        </table>
        <a class="btn btn-success" type="button"
           href="{{ route('products.index') }}">Назад</a>
    </div>
@endsection
