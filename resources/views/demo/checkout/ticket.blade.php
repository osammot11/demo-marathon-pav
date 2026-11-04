@extends('demo.checkout.layout')
@section('title', __($demo['translation_key'].'checkout_tickets'))
@section('content')
<div class="checkout-card">
    <p class="eyebrow">{{ __($demo['translation_key'].'checkout_tickets') }}</p>
    <h1>{{ $demo['name'] }}</h1>
    <div class="registration-fact"><span>{{ $ticket->first_name }} {{ $ticket->last_name }}</span><strong>{{ $ticket->route_choice }}</strong></div>
    <div class="registration-fact"><span>{{ __($demo['translation_key'].'checkout_order') }}</span><strong>{{ $ticket->order->group_code }}</strong></div>
    <div class="registration-fact"><span>Ticket code</span><strong>{{ $ticket->unique_ticket_code }}</strong></div>
    @if(data_get($demo, 'operations.shuttle'))
        <div class="registration-fact"><span>{{ __($demo['translation_key'].'ticket_shuttle') }}</span><strong>{{ $ticket->shuttle_needed ? ($ticket->shuttle_assignment ?: __($demo['translation_key'].'ticket_shuttle_pending')) : __($demo['translation_key'].'ticket_shuttle_no') }}</strong></div>
    @endif
    @if(data_get($demo, 'operations.bib_pickup'))
        <div class="registration-fact"><span>{{ __($demo['translation_key'].'ticket_bib') }}</span><strong>{{ $ticket->bib_number ?: __($demo['translation_key'].'ticket_bib_pending') }}</strong></div>
        <div class="registration-fact"><span>{{ __($demo['translation_key'].'ticket_bib_pickup') }}</span><strong>{{ $ticket->bib_collected_at ? __($demo['translation_key'].'ticket_bib_collected') : __($demo['translation_key'].'ticket_bib_not_collected') }}</strong></div>
    @endif
    <p>{{ __($demo['translation_key'].'test_mode_label') }}</p>
</div>
@endsection
