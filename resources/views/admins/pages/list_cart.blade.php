@extends('admins.index')
@section('admin')
<div class="accordion" id="accordionExample">
@foreach ($item_cart as $user)
@if ($user->cartItems->isNotEmpty())
<div class="accordion" id="accordionExample">
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button  collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                aria-expanded="true">
                {{-- {{$item->name }} --}}
               nama pembeli :{{ $user->username }}
            </button>
        </h2>

        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
        @foreach ($user->cartItems as $item)
            @php
                $totalPrice = 0;
                $totalQuantity = 0;
            @endphp

            <div class="accordion-body">
                @php
                $itemTotalPrice = $item->barang->harga * $item->quantity;
                $totalPrice += $itemTotalPrice;
                $totalQuantity += $item->quantity;
                @endphp
                nama barang : {{ $item->barang->title}}
                <br>
                <img class="" width="70%" src="{{ asset('images/' . $item->barang->img) }}" alt="">
                <br>
                harga barang : {{$item->barang->harga}} K
                <br>
                quantity : {{$item->quantity}}
                <br>
                total harga: {{ number_format($itemTotalPrice, 2, ',', '.') }}
            </div>
            <hr class="hr" />
         @endforeach
            @php
            // Terapkan diskon 15%
            $discount = 0.15;
            $discountAmount = $totalPrice * $discount;
            $finalPrice = $totalPrice - $discountAmount;
            @endphp

            <div class="accordion-body">
                <p>Total harga sebelum diskon: IDR {{ number_format($totalPrice, 2, ',', '.') }}</p>
                <p>Diskon 15%: IDR {{ number_format($discountAmount, 2, ',', '.') }}</p>
                <p>Total harga setelah diskon: IDR {{ number_format($finalPrice, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach
</div>
@endsection