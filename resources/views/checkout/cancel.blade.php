@extends('checkout.layout')
@section('title', __('crotrail.checkout_cancel'))
@section('content')
<div class="checkout-card"><p class="eyebrow">{{ __('crotrail.checkout_order') }} {{ $order->group_code }}</p><h1>{{ __('crotrail.checkout_cancel') }}</h1><p>{{ __('crotrail.checkout_cancel_text') }}</p>
@if($order->payment_method === 'stripe')<a class="button" href="{{ route('checkout.payment', $order->group_code) }}">{{ __('crotrail.checkout_retry') }} ↗</a>@else<a class="button" href="{{ route(app()->getLocale() === 'en' ? 'en.registration' : 'registration', str_replace('CroTrail ', '', $order->tickets()->first()->route_choice)) }}">{{ __('crotrail.checkout_back') }} ↗</a>@endif</div>
@endsection
