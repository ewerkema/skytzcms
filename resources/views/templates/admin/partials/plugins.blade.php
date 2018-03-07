<script src="/plugins/lean-slider/lean-slider.js"></script>
<link rel="stylesheet" href="/plugins/lean-slider/lean-slider.css" type="text/css" />
<script src="/plugins/colorbox/jquery.colorbox.js"></script>
<link rel="stylesheet" href="/plugins/colorbox/colorbox.css" />
<script src="/plugins/whatspop.js"></script>
<script src='https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit' async defer></script>
<script type="text/javascript">
    var CaptchaCallback = function() {
        $('.g-recaptcha').each(function(index, el) {
            grecaptcha.render(el, {'sitekey' : '{{ config('skytz.recaptcha_public') }}'});
        });
    };
</script>