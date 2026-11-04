@extends('checkout.layout')
@section('title', __('crotrail.checkout_success'))
@section('content')
<div class="checkout-card"><p class="eyebrow">{{ __('crotrail.checkout_order') }} {{ $order->group_code }}</p><h1>{{ $order->tickets->contains(fn ($ticket) => $ticket->status === 'pending_review') ? __('crotrail.checkout_review_title') : (config('crotrail.test_mode') ? __('crotrail.test_success') : __('crotrail.checkout_success')) }}</h1><p>{{ $order->tickets->contains(fn ($ticket) => $ticket->status === 'pending_review') ? __('crotrail.checkout_review_text') : __('crotrail.checkout_thanks') }}</p><h2>{{ $order->tickets->contains(fn ($ticket) => $ticket->status === 'pending_review') ? __('crotrail.checkout_entry_status') : __('crotrail.checkout_tickets') }}</h2>
@foreach($order->tickets as $ticket)<div class="registration-fact"><span>{{ $ticket->first_name }} {{ $ticket->last_name }} · {{ $ticket->route_choice }}</span>@if($ticket->status === 'active' && $ticket->unique_ticket_code)<a class="text-link" href="{{ route('ticket.download', $ticket->unique_ticket_code) }}">{{ __('crotrail.checkout_download') }} ↗</a>@else<strong>{{ __('crotrail.checkout_review_status') }}</strong>@endif</div>@endforeach
<a class="text-link checkout-home" href="{{ app()->getLocale() === 'en' ? route('en.home') : route('home') }}">← CroTrail</a></div>
@endsection
