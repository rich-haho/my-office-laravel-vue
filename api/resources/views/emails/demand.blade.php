@component('mail::message')
#### {{ __('mail.demand.title') }}
@slot('subcopy')
##### {{ __('mail.demand.object') }}
{{ $object }}

##### {{ __('mail.demand.description') }}
{{ $description }}

##### {{ __('mail.demand.category') }}
{{ $category }}

##### {{ __('mail.demand.contact') }}
{{ $contact }}
@endslot
@endcomponent
