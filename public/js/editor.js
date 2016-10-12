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

var editor;
var editorButtons = new ButtonGroup('#changePage, #changeLayout, #hideLayout', '#revertChanges, #saveChanges', '#saveLayout');

window.addEventListener('load', function() {
    editor = ContentTools.EditorApp.get();
    editor.init('*[data-editable]', 'data-name');
    editor._ignition.unmount();
    editor.revert = function() {
        this.revertToSnapshot(this.history.goTo(0), false);
        return true;
    };

    editor.addEventListener('saved', function (ev) {
        // Save the changes ...
        var content = ev.detail().regions;
        var newBlocks = Object.keys(content).length;

        if (newBlocks) {
            $.ajax({
                url: '/cms/pages/'+page_id+'/content',
                type: 'PATCH',
                data: {
                    'content' : content
                },
                success: function (data) {
                    swal({
                        title: 'Pagina opgeslagen',
                        type: 'success',
                        timer: 2000
                    });
                },
                error: function (data) {
                    showError(data);
                    console.log(data);
                }
            });
        } else {
            swal({
                title: 'Geen wijzingen gemaakt',
                type: 'info',
                timer: 2000
            });
        }
    });
});

var grid;
var blockContent = $(".page-content");
var page_id = blockContent.attr("data-page");
var elements;

function enableGridstack() {
    blockContent.addClass("grid-stack");
    blockContent.css("height", "");
    elements = [];

    blockContent.find(".row").each(function() {
        var element = $(this);
        var content = element.html();
        element.replaceWith(content);
    });

    blockContent.find('*[data-editable]').each(function() {
        var element = $(this);
        var content = element.html();
        element.addClass("grid-stack-item");
        element.removeClass("medium-offset-0 medium-offset-1 medium-offset-2 medium-offset-3 medium-offset-4 medium-offset-5 medium-offset-6 medium-offset-7 medium-offset-8 medium-offset-9 medium-offset-10 medium-offset-11");
        element.html("<div class='grid-stack-item-content'></div>");
        element.find(".grid-stack-item-content").html(content);
    });

    var buttonContainer =  $('<div class="buttonContainer container flex-center"></div>');
    buttonContainer.append('<button class="newBlock" onclick="addWidget()"><span class="glyphicon glyphicon-plus"></span> Nieuw blok toevoegen</button>');
    buttonContainer.insertAfter(blockContent);


    var options = {
        cellHeight: 80,
        verticalMargin: 10,
        animate: true,
        float: true
    };

    $('.grid-stack').gridstack(options);
    grid = $('.grid-stack').data('gridstack');
    grid.enable();
}

function addWidget() {
    var name = "block" + (lastElementId() + 1);
    var element = $('<div class="grid-stack-item" data-name="'+name+'" data-editable><div class="grid-stack-item-content"></div></div>');
    var x = 0;
    var y = lastRow() + 1;
    var width = 12;
    var height = 1;
    grid.addWidget(element, x, y, width, height);
}

function lastRow() {
    var maxRow = 0;
    blockContent.find('*[data-editable]').each(function() {
        maxRow = Math.max(maxRow, $(this).attr('data-gs-y'));
    });

    return maxRow;
}

function getNumberFromString (string) {
    return parseInt(string.match(/(\d+)$/)[0], 10);
}

function lastElementId() {
    var id = 0;
    blockContent.find('*[data-editable]').each(function() {
        var name = $(this).attr('data-name');
        id = Math.max(id, getNumberFromString(name));
    });
    return id;
}

function saveGrid() {
    blockContent.removeClass("grid-stack");
    blockContent.css("height", "auto");
    $('.buttonContainer').remove();

    var content = {};
    blockContent.find('*[data-editable]').each(function() {
        var element = $(this);
        var name = element.attr('data-name');
        content[name] = {};
        content[name]['x'] = element.attr('data-gs-x');
        content[name]['y'] = element.attr('data-gs-y');
        content[name]['width'] = element.attr('data-gs-width');
        content[name]['height'] = element.attr('data-gs-height');
    });

    console.log(content);

    grid.destroy(false);

    $.ajax({
        url: '/cms/pages/'+page_id+'/grid',
        type: 'PATCH',
        data: {
            'content' : content
        },
        error: function (data) {
            showError(data.message);
        }
    });
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
    $.when(
        saveGrid(),
        reloadPageContent()
    ).then(function() {
        editorButtons.showEdit();

        swal({
            title: 'Indeling opgeslagen',
            type: 'success',
            timer: 2000
        });
    });
}

function showError(data) {
    console.log(data);

    var response = $.parseHTML(data.responseText);
    var message = (response.message != undefined) ? response.message : 'Er ging iets fout bij het opslaan!';
    swal(
        'Oops...',
        message,
        'error'
    )
}
