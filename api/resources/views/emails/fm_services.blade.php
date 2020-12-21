@component('mail::message')
#### {{ __('mail.fm_services.title') }}
@slot('subcopy')
##### {{ __('mail.fm_services.object') }}
{{ $object }}

##### {{ __('mail.fm_services.description') }}
{{ $description }}

##### {{ __('mail.fm_services.contact') }}
{{ $contact }}

##### {{ __('mail.fm_services.building') }}
{{ $building }}
@endslot
@endcomponent
