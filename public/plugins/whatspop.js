$(document).ready(function() {
    let element = $('.whatspop');

    if (element !== undefined) {
        element.find('.whatspop-button').click(function() {
            element.toggleClass('open-panel');
        });

        let timer = parseInt(element.data('delay'));
        setTimeout(function(){
            element.addClass('visible');
        }, timer*1000);
    }
});