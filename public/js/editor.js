var ButtonGroup = function(editButtons, saveContent, saveLayout) {
    this.editButtons = editButtons;
    this.saveContent = saveContent;
    this.saveLayout = saveLayout;
};

ButtonGroup.prototype.showEdit = function() {
    $(this.editButtons).show();
    $(this.saveContent).hide();
    $(this.saveLayout).hide();
};

ButtonGroup.prototype.showSaveContent = function() {
    $(this.editButtons).hide();
    $(this.saveContent).show();
    $(this.saveLayout).hide();
};

ButtonGroup.prototype.showSaveLayout = function() {
    $(this.editButtons).hide();
    $(this.saveContent).hide();
    $(this.saveLayout).show();
};

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var editor;
var editorButtons = new ButtonGroup('#changePage, #changeLayout, #hideLayout', '#revertChanges, #saveChanges', '#saveLayout');

window.addEventListener('load', function() {
    editor = ContentTools.EditorApp.get();
    editor.init('*[data-editable]', 'data-name');
    editor._ignition.unmount();
    editor.revert = function() {
        this.revertToSnapshot(this.history.goTo(0), false);
        return true;
    }

    editor.addEventListener('saved', function (ev) {
        // Save the changes ...
        var blocks = ev.detail().regions;

        $.ajax({
            url: '/cms/pages/'+page_id+'/blocks',
            type: 'PATCH',
            data: {
                'blocks' : blocks
            },
            success: function (data) {
                swal({
                    title: 'Pagina opgeslagen',
                    type: 'success',
                    timer: 2000
                });
                console.log(data);
            },
            error: function (data) {
                showError(data);
                console.log(data);
            }
        });
    });
});

var grid;
var blockContent = $(".page-content");
var page_id = blockContent.attr("data-page");
var elements;

function enableGridstack() {
    blockContent.addClass("grid-stack");
    blockContent.css("height", "");
    elements = {};

    $.get('/cms/pages/'+page_id+'/content', function(content) {
        _.each(content, function (item, name) {
            elements[name] = item;
            addGridstackToElement(item, name);
        });

        var options = {
            cellHeight: 80,
            verticalMargin: 10,
            animate: true,
            float: true
        };

        $('.grid-stack').gridstack(options);
        grid = $('.grid-stack').data('gridstack');
        grid.enable();
    });
}

function addGridstackToElement(item, name) {
    var element = blockContent.find("[data-name='" + name + "']");

    if (element !== undefined) {
        var content = element.html();
        element.addClass("grid-stack-item");
        element.removeClass("medium-offset-0 medium-offset-1 medium-offset-2 medium-offset-3 medium-offset-4 medium-offset-5 medium-offset-6 medium-offset-7 medium-offset-8 medium-offset-9 medium-offset-10 medium-offset-11");
        element.attr('data-gs-x', item.offset);
        element.attr('data-gs-y', item.row);
        element.attr('data-gs-width', item.width);
        element.attr('data-gs-height', item.height);
        element.html("<div class='grid-stack-item-content'></div>");
        element.find(".grid-stack-item-content").html(content);
    } else {
        console.log("Couldn't find block with name : "+name);
    }
}

function disableGridstack(callback) {
    grid.destroy(false);
    blockContent.removeClass("grid-stack");
    blockContent.css("height", "auto");

    _.each(elements, function(item,name) {
        var element = blockContent.find("[data-name='" + name + "']");
        elements[name]['offset'] = element.attr('data-gs-x');
        elements[name]['row'] = element.attr('data-gs-y');
        elements[name]['width'] = element.attr('data-gs-width');
        elements[name]['height'] = element.attr('data-gs-height');

        removeGridstackFromElement(item,name);
    });

    $.ajax({
        url: '/cms/pages/'+page_id+'/content',
        type: 'PATCH',
        data: {
            'content' : elements
        },
        success: callback,
        error: function (data) {
            showError(data.message);
            console.log(data);
        }
    });
}

function removeGridstackFromElement(item, name) {
    var element = blockContent.find("[data-name='" + name + "']");

    if (element !== undefined) {
        var content = element.find(".grid-stack-item-content").html();
        element.removeClass("grid-stack-item");
        element.attr('data-gs-x', '');
        element.attr('data-gs-y', '');
        element.attr('data-gs-width', '');
        element.attr('data-gs-height', '');
        element.html(content);
    } else {
        console.log("Couldn't find block with name : "+name);
    }
}

function revertChanges() {
    swal({
        title: "Wijzigingen annuleren?",
        text: "Je kan deze wijzingen niet meer herstellen.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ja, verwijder de wijzingen",
    }).then(function(){
        editor._ignition.cancel();
        editor._ignition.unmount();
        editorButtons.showEdit();
    });
}

function saveChanges() {
    editor._ignition.confirm();
    editor._ignition.unmount();
    editorButtons.showEdit();
}


function changePage() {
    editor._ignition.edit();
    editor._ignition.unmount();
    editorButtons.showSaveContent();
}

function changeLayout() {
    enableGridstack();
    editor._ignition.confirm();
    editor._ignition.unmount();
    editorButtons.showSaveLayout();
}

function saveLayout() {
    disableGridstack(function() {
        editorButtons.showEdit();
        loadPageContent();
        swal({
            title: 'Indeling opgeslagen',
            type: 'success',
            timer: 2000
        });
    });
}

function showError(data) {
    var response = $.parseJSON(data.responseText)
    var message = (response.message != undefined) ? response.message : 'Er ging iets fout bij het opslaan!';
    swal(
        'Oops...',
        message,
        'error'
    )
}
