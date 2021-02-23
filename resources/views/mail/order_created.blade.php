<p>Уважаемый {{ $name }}</p>

<strong>Ваш заказ №{{ $orderId }} на сумму: {{ $orderSum }} создан</strong>

<p>Список товаров:</p>

<table>
    <tr>
        <td>Название</td>
        <td>Заказанное кол-во</td>
        <td>Цена за шт</td>
        <td>Общая стоимость</td>
    </tr>
    @foreach($orderProducts as $product)
        <tr>
            <td>
                {{ $product->name }}
            </td>
            <td>
                {{ $product->pivot->count }}
            </td>
            <td>
                {{ $product->price }}
            </td>
            <td>
                {{ $product->getPriceForCount() }}
            </td>
        </tr>
    @endforeach
</table>
