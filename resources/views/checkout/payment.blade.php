@extends('checkout.layout')
@section('title', __('crotrail.checkout_payment_title'))
@section('head')<script src="https://js.stripe.com/v3/"></script>@endsection
@section('content')
<div class="checkout-card">
    <p class="eyebrow">{{ __('crotrail.checkout_order') }} {{ $order->group_code }}</p>
    <h1>{{ __('crotrail.checkout_payment_title') }}</h1>
    <p>{{ __('crotrail.checkout_payment_intro') }}</p>
    <div class="registration-fact"><span>{{ __('crotrail.checkout_participants') }}</span><strong>{{ $order->tickets->count() }}</strong></div>
    <div class="registration-fact"><span>{{ __('crotrail.checkout_total') }}</span><strong>€ {{ number_format($order->total_amount, 2, ',', '.') }}</strong></div>
    <form id="payment-form"><div id="payment-element"></div><p id="payment-message" class="payment-message" role="alert" hidden></p><button class="button" id="submit" type="submit">{{ __('crotrail.checkout_pay') }} € {{ number_format($order->total_amount, 2, ',', '.') }}</button></form>
    <a class="text-link" href="{{ route(app()->getLocale() === 'en' ? 'en.registration' : 'registration', str_replace('CroTrail ', '', $order->tickets->first()->route_choice)) }}">← {{ __('crotrail.checkout_back') }}</a>
</div>
<script>
const stripe = Stripe(@json($stripeKey));
const elements = stripe.elements({clientSecret: @json($clientSecret), appearance: {theme:'stripe',variables:{colorPrimary:'#137b79',fontFamily:'DM Sans, sans-serif'}}});
elements.create('payment').mount('#payment-element');
const form = document.getElementById('payment-form');
const button = document.getElementById('submit');
const message = document.getElementById('payment-message');
form.addEventListener('submit', async event => {
    event.preventDefault(); button.disabled = true; message.hidden = true;
    const {error, paymentIntent} = await stripe.confirmPayment({elements, confirmParams:{return_url:@json(route('checkout.success', ['order_code'=>$order->group_code]))}, redirect:'if_required'});
    if (error) { message.textContent = error.message || @json(__('crotrail.checkout_error')); message.hidden = false; button.disabled = false; return; }
    if (paymentIntent && paymentIntent.status === 'succeeded') { window.location.href = @json(route('checkout.success', ['order_code'=>$order->group_code])); return; }
    message.textContent = @json(__('crotrail.checkout_pending')); message.hidden = false; button.disabled = false;
});
</script>
@endsection
