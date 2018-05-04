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
<div id="fb-root"></div>
<script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = 'https://connect.facebook.net/nl_NL/sdk.js#xfbml=1&version=v3.0&appId=188394265318465&autoLogAppEvents=1';
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>