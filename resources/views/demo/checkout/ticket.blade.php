@extends('demo.checkout.layout')
@section('title', __($demo['translation_key'].'checkout_tickets'))
@section('content')
<div class="checkout-card">
    <p class="eyebrow">{{ __($demo['translation_key'].'checkout_tickets') }}</p>
    <h1>{{ $demo['name'] }}</h1>
    <div class="registration-fact"><span>{{ $ticket->first_name }} {{ $ticket->last_name }}</span><strong>{{ $ticket->route_choice }}</strong></div>
    <div class="registration-fact"><span>{{ __($demo['translation_key'].'checkout_order') }}</span><strong>{{ $ticket->order->group_code }}</strong></div>
    <div class="registration-fact"><span>Ticket code</span><strong>{{ $ticket->unique_ticket_code }}</strong></div>
    <p>{{ __($demo['translation_key'].'test_mode_label') }}</p>
</div>
@endsection
