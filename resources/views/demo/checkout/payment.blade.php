@extends('demo.checkout.layout')
@section('title', __($demo['translation_key'].'checkout_payment_title'))
@section('head')<script src="https://js.stripe.com/v3/"></script>@endsection
@section('content')
<div class="checkout-card">
    <p class="eyebrow">{{ __($demo['translation_key'].'checkout_order') }} {{ $order->group_code }}</p>
    <h1>{{ __($demo['translation_key'].'checkout_payment_title') }}</h1>
    <p>{{ __($demo['translation_key'].'checkout_payment_intro') }}</p>
    <div class="registration-fact"><span>{{ __($demo['translation_key'].'checkout_participants') }}</span><strong>{{ $order->tickets->count() }}</strong></div>
    <div class="registration-fact"><span>{{ __($demo['translation_key'].'checkout_total') }}</span><strong>€ {{ number_format($order->total_amount, 2, ',', '.') }}</strong></div>
    <form id="payment-form"><div id="payment-element"></div><p id="payment-message" class="payment-message" role="alert" hidden></p><button class="button" id="submit" type="submit">{{ __($demo['translation_key'].'checkout_pay') }} € {{ number_format($order->total_amount, 2, ',', '.') }}</button></form>
    <a class="text-link" href="{{ route(app()->getLocale() === 'en' ? 'demo.en.registration' : 'demo.registration', ['demo' => $demo['slug'], 'race' => $order->race_slug ?: ($order->tickets->first()?->race_slug ?: array_key_first($demo['races']))]) }}">← {{ __($demo['translation_key'].'checkout_back') }}</a>
</div>
<script>
const stripe = Stripe(@json($stripeKey));
const elements = stripe.elements({clientSecret: @json($clientSecret)});
elements.create('payment').mount('#payment-element');
const form = document.getElementById('payment-form');
const button = document.getElementById('submit');
const message = document.getElementById('payment-message');
form.addEventListener('submit', async event => {
    event.preventDefault(); button.disabled = true; message.hidden = true;
    const successUrl = @json(route('demo.checkout.success', ['demo' => $demo['slug'], 'order_code' => $order->group_code]));
    const {error, paymentIntent} = await stripe.confirmPayment({elements, confirmParams:{return_url:successUrl}, redirect:'if_required'});
    if (error) { message.textContent = error.message || @json(__($demo['translation_key'].'checkout_error')); message.hidden = false; button.disabled = false; return; }
    if (paymentIntent && paymentIntent.status === 'succeeded') { window.location.href = successUrl; return; }
    message.textContent = @json(__($demo['translation_key'].'checkout_pending')); message.hidden = false; button.disabled = false;
});
</script>
@endsection
