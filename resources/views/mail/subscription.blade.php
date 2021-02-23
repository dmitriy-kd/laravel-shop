<p>Уважаемый покупатель, товар {{ $product->name }} в данный момент находится в наличии!</p>

<a href="{{ route('product', [$product->category->code, $product->code]) }}">Узнать подробности</a>
