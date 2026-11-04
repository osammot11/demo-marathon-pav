@extends('demo.checkout.layout')
@section('title', __($demo['translation_key'].'checkout_cancel'))
@section('content')
<div class="checkout-card">
    <p class="eyebrow">{{ __($demo['translation_key'].'checkout_order') }} {{ $order->group_code }}</p>
    <h1>{{ __($demo['translation_key'].'checkout_cancel') }}</h1>
    <p>{{ __($demo['translation_key'].'checkout_cancel_text') }}</p>
    <a class="button" href="{{ route('demo.checkout.payment', ['demo' => $demo['slug'], 'order_code' => $order->group_code]) }}">{{ __($demo['translation_key'].'checkout_retry') }} ↗</a>
</div>
@endsection
