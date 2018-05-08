@include('templates.admin.partials.whatsapp')
@if (!is_cms())
    @include('cookieConsent::index')
@endif