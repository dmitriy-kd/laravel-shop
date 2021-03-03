<div class="col-sm-6 col-md-4">
    <div class="thumbnail">
        <div class="labels">
            @if($product->isNew())
                <span class="badge badge-success" style="margin-bottom:5px;">Новинка</span>
            @endif
            @if($product->isRecommend())
                <span class="badge badge-warning" style="margin-bottom:5px;">Рекомендуемые</span>
            @endif
            @if($product->isHit())
                <span class="badge badge-danger" style="margin-bottom:5px;">Хит продаж</span>
            @endif
        </div>
        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
        <div class="caption">
            <h3><a href="#">{{ $product->__('name') }}</a></h3>
            <p>{{ $product->price }} ₽</p>
            <p>
            @if ($product->isAvailable())
                <form action="{{ route('basket-add', $product->id) }}" method="POST" style="display:inline-block">
                    <button type="submit" class="btn btn-primary" role="button">В корзину</button>
                    @csrf
                </form>
            @else
{{--                <button class="btn btn-default">Нет в наличии</button>--}}
                Не доступен
            @endif
            <a href="{{ route('product', [isset($category) ? $category->code : $product->category->code, $product->code]) }}"
               class="btn btn-default"
               role="button">Подробнее</a>
            <br>
            {{ $product->category->name }}
            </p>
        </div>
    </div>
</div>
