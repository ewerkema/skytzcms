if (document.getElementById('websiteForm')) {
    let request = new Request('/cms/settings');
    request.setType('PATCH');
    request.setForm('#websiteForm');

    request.addFields([
        'footerblock',
        'googleanalytics',
        'facebook_page',
        'twitter_page',
        'youtube_page',
        'googleplus_page',
        'header_id',
        'whatsapp_number',
        'whatsapp_display_number',
        'whatsapp_text',
        'whatsapp_timer'
    ]);

    request.addField('recordgoogle', 'checkbox', false);
    request.addField('display_whatsapp', 'checkbox', false);

    request.onSubmit(function(data) {
        $('#websiteModal').modal('toggle');
        swal({
            title: 'Success!',
            text: 'Website instellingen zijn succesvol aangepast.',
            type: "success",
            timer: 2000
        });
    });

    let sliderSelect = $('[name=header_slider]');
    let imageInput = $('[name=header_image]');
    let imageInputName = $('[name=header_image_name]');

    sliderSelect.change(function() {
        let value = $(this).val();

        if (value) {
            imageInput.val(0);
            imageInputName.val("");
        }
    });

    imageInput.change(function() {
        let value = $(this).val();

        if (value)
            sliderSelect.val(sliderSelect.find('option:first').val());
    });
}

if (document.getElementById('accountForm')) {
    let request = new Request('/cms/users/current');
    request.setType('PATCH');
    request.setForm('#accountForm');

    request.addFields(['firstname', 'lastname', 'username', 'email']);
    request.addOptionalFields(['password', 'password_confirmation']);

    request.onSubmit(function(data) {
        $('#accountModal').modal('toggle');
        swal({
            title: 'Success!',
            text: 'Account is succesvol aangepast.',
            type: "success",
            timer: 2000
        });
        $('#userName').html(data.firstname + " " + data.lastname);
    });
}

if (document.getElementById('pageForm')) {
    let request = new Request('/cms/pages/' + window.currentPage);
    request.setType('PATCH');
    request.setForm('#pageForm');

    request.addFields(['title', 'meta_title', 'meta_desc', 'meta_keywords', 'header_id']);
    request.addField('slug', 'text', 'index');

    request.onSubmit(function(data) {
        if (data['redirectTo'] === undefined) {
            request.getForm().find('.form-message').addClass("alert-danger").html("Er is iets onverwachts gebeurd, probeer het later opnieuw.").show();
            return;
        }

        window.location.href = '/cms/' + data['redirectTo'];
    });

    $('#deletePage').click(function() {
        swal({
            title: "Weet je het zeker?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ja, verwijder deze pagina",
        }).then(function(){
            $.ajax({
                url: '/cms/pages/' + window.currentPage,
                type: 'POST',
                data: {
                _method: 'DELETE'
            },
            success: function(data) {
                if (data['redirectTo'] === undefined) {
                    request.getForm().find('.form-message').addClass("alert-danger").html("Er is iets onverwachts gebeurd, probeer het later opnieuw.").show();
                    return;
                }

                window.location.href = data['redirectTo'];
            },
            error: function (errorData) {
                request.showError(errorData)
            }
        });
        }).done();
    });
}

function enableAutomaticSlug() {
    let slugActive = true;
    $('.page-name-listener').on('input', function() {
        if (slugActive) {
            $(this).closest('form').find('.page-slug-listener').val(toSlug($(this).val()));
        }
    });

    $('.page-slug-listener').keypress(function() {
        slugActive = false;
    });
}

function enableAutomaticTitle() {
    let titleActive = true;
    $('.page-name-listener').on('input', function() {
        if (titleActive) {
            $(this).closest('form').find('.page-title-listener').val($(this).val());
        }
    });

    $('.page-slug-listener').keypress(function() {
        titleActive = false;
    });
}

function toSlug(str) {
    str = str.replace(/^\s+|\s+$/g, ''); // trim
    str = str.toLowerCase();

    // remove accents, swap ñ for n, etc
    let from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;";
    let to   = "aaaaaeeeeeiiiiooooouuuunc------";
    for (let i=0, l=from.length ; i<l ; i++) {
        str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
    }

    str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
        .replace(/\s+/g, '-') // collapse whitespace and replace by -
        .replace(/-+/g, '-'); // collapse dashes

    return str;
}

if (document.getElementById('newPageForm')) {
    let request = new Request('/cms/pages');
    request.setType('POST');
    request.setForm('#newPageForm');

    request.addFields(['title', 'meta_title', 'meta_desc', 'header_id', 'parent_id']);
    request.addField('slug', 'text', 'index');
    request.addField('menu', 'checkbox');

    let subpageSelect = $('[name=parent_id]');
    let visibleInMenu = $('[name=menu]');
    subpageSelect.change(function() {
        if ($(this).val())
            visibleInMenu.prop('checked', true);
    });

    visibleInMenu.change(function() {
        if (!$(this).is(":checked"))
            subpageSelect.val(subpageSelect.find('option:first').val());
    });

    request.onSubmit(function(data) {
        if (data['redirectTo'] === undefined) {
            request.getForm().find('.form-message').addClass("alert-danger").html("Er is iets onverwachts gebeurd, probeer het later opnieuw.").show();
            return;
        }

        window.location.href = '/cms/' + data['redirectTo'];
    });

    enableAutomaticSlug();
    enableAutomaticTitle();
}