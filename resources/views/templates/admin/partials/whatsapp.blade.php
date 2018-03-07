@if (Setting::get('display_whatsapp'))
    <div class="whatspop" data-delay="{{ Setting::get('whatsapp_timer') }}">
        <a href="whatsapp://send?text=&amp;phone={{ Setting::get('whatsapp_number') }}" class="whatspop-panel">{{ Setting::get('whatsapp_text') }}<span>{{ Setting::get('whatsapp_display_number') }}</span></a>
        <img class="whatspop-button" src="{{ asset('whatsapp2.png') }}" alt="img">
        <span class="numb">1</span>
    </div>
@endif