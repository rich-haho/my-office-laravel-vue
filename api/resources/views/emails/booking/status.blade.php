@component('mail::message')
# {{ __('Hello :name,', ['name' => $booking->user->name]) }}

@lang('enum.bookingstatus.values.' . $booking->status)

@slot('subcopy')
    <td class="content-cell">
        <img src="{{ $booking->product->image }}" width="200" alt="{{ $booking->product->name }}">
        <h5>
            <b>{{ __('Product: :name', ['name' => $booking->product->name]) }}</b>
        </h5>
        <h6>
            {{ $booking->product->description }}
        </h6>
        <h6>
            <b>{{ $booking->product->price }} {{ strtoupper($booking->product->currency) }}</b>
        </h6>
    </td>
@endslot
@endcomponent
