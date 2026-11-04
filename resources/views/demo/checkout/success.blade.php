@extends('demo.checkout.layout')
@section('title', __($demo['translation_key'].'checkout_success'))
@section('content')
<div class="checkout-card">
    <p class="eyebrow">{{ __($demo['translation_key'].'checkout_order') }} {{ $order->group_code }}</p>
    <h1>{{ $order->tickets->contains(fn ($ticket) => $ticket->status === 'pending_review') ? __($demo['translation_key'].'checkout_review_title') : __($demo['translation_key'].'test_success') }}</h1>
    <p>{{ $order->tickets->contains(fn ($ticket) => $ticket->status === 'pending_review') ? __($demo['translation_key'].'checkout_review_text') : __($demo['translation_key'].'checkout_thanks') }}</p>
    @foreach($order->tickets as $ticket)
        <div class="registration-fact"><span>{{ $ticket->first_name }} {{ $ticket->last_name }} · {{ $ticket->route_choice }}</span>
            @if($ticket->status === 'active' && $ticket->unique_ticket_code)
                <a class="text-link" href="{{ route('demo.ticket', ['demo' => $demo['slug'], 'unique_code' => $ticket->unique_ticket_code]) }}">{{ __($demo['translation_key'].'checkout_ticket_view') }} ↗</a>
            @else
                <strong>{{ __($demo['translation_key'].'checkout_review_status') }}</strong>
            @endif
        </div>
    @endforeach
    <a class="text-link checkout-home" href="{{ route(app()->getLocale() === 'en' ? 'demo.en.home' : 'demo.home', ['demo' => $demo['slug']]) }}">← {{ $demo['name'] }}</a>
</div>
@endsection
