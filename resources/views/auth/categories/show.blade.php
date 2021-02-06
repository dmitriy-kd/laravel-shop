@extends('auth.layouts.master')

@section('title', $category->name)

@section('content')
    <div class="col-md-12">
        <h1>Категория {{ $category->name }}</h1>
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
                <td>{{ $category->id }}</td>
            </tr>
            <tr>
                <td>Код</td>
                <td>{{ $category->code }}</td>
            </tr>
            <tr>
                <td>Название</td>
                <td>{{ $category->name }}</td>
            </tr>
            {{--<tr>
                <td>Название en</td>
                <td>{{ $category->name_en }}</td>
            </tr>--}}
            <tr>
                <td>Описание</td>
                <td>{{ $category->description }}</td>
            </tr>
            {{--<tr>
                <td>Описание en</td>
                <td>{{ $category->description_en }}</td>
            </tr>--}}
            <tr>
                <td>Кол-во товаров</td>
                <td>{{ $category->products->count() }}</td>
            </tr>
            <tr>
                <td>Изображение</td>
                <td><img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" height="240px"></td>
            </tr>
            </tbody>
        </table>
        <a class="btn btn-success" type="button"
           href="{{ route('categories.index') }}">Назад</a>
    </div>
@endsection
