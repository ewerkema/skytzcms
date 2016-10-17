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
    addPagesToEditor(ContentTools);
    editor.revert = function() {
        this.revertToSnapshot(this.history.goTo(0), false);
        return true;
    };

    editor.addEventListener('saved', function (ev) {
        // Save the changes ...
        var content = ev.detail().regions;
        var newBlocks = Object.keys(content).length;

        if (newBlocks) {
            $.when(
                saveContent(content)
            ).then(function() {
                editorButtons.showEdit();

                swal({
                    title: 'Pagina opgeslagen',
                    type: 'success',
                    timer: 2000
                });
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

function saveContent(content) {
    $.ajax({
        url: '/cms/pages/'+page_id+'/content',
        type: 'PATCH',
        data: {
            'content' : content
        },
        error: function (data) {
            showError(data);
            console.log(data);
        }
    });
}

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
        element.addClass("grid-stack-item");
        element.removeOffsets();
        element.moveDownIn('grid-stack-item-content').addGridstackMenu();
    });

    var buttonContainer =  $('<div class="buttonContainer container flex-center"></div>');
    buttonContainer.append('<button class="newBlock" onclick="addWidget()"><span class="glyphicon glyphicon-plus"></span> Nieuw blok toevoegen</button>');
    buttonContainer.insertAfter(blockContent);


    var options = {
        cellHeight: 80,
        verticalMargin: 10,
        animate: true,
        float: true,
        resizable: {
            autoHide: false,
            handles: 'e,w'
        }
    };

    $('.grid-stack').gridstack(options);
    grid = $('.grid-stack').data('gridstack');
    grid.enable();

    $('.grid-stack').on('resizedone', function(event) {
        var element = $(event.target);
        var width = element.attr('data-gs-width');
        element.find('.grid-stack-title').html(textWidth(width));

    });
}

function textWidth (width) {
    switch (parseInt(width)) {
        case 2: return "1/6";
        case 3: return "1/4";
        case 4: return "1/3";
        case 6: return "1/2";
        case 8: return "2/3";
        case 9: return "3/4";
        case 10: return "5/6";
        case 12: return "1/1";
        default: return width+"/12";
    }
}

$.fn.moveDownIn = function(classname) {
    var content = this.html();
    this.html("<div class='"+classname+"'></div>");
    return this.children("."+classname).html(content);
};

$.fn.removeOffsets = function() {
    for (var i = 1; i < 12; i++) {
        this.removeClass("lg-offset-"+i)
            .removeClass("md-offset-"+i)
            .removeClass("sm-offset-"+i)
            .removeClass("xs-offset-"+i);
    }
};

$.fn.addGridstackMenu = function() {
    var width = this.parent(".grid-stack-item").attr('data-gs-width');
    width = (width == undefined) ? 12 : width;
    this.append("<div class='grid-stack-menu'><h3 class='grid-stack-title'>"+textWidth(width)+"</h3><div class='remove'><span class='glyphicon glyphicon-remove'></span></div></div>")
        .append("<div class='resize-icon resize-icon__left'><span class='stripe'></span><span class='stripe'></span></div>")
        .append("<div class='resize-icon resize-icon__right'><span class='stripe'></span><span class='stripe'></span></div>");

    this.on('click', '.remove', function (e) {
        e.preventDefault();
        var el = this.closest('.grid-stack-item');
        swal({
            title: "Blok verwijderen?",
            text: "Je kan deze wijzingen niet meer herstellen.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ja, verwijder dit blok",
        }).then(function(){
            grid.removeWidget(el);
        });
    })
};

function addWidget() {
    var name = "block" + (lastElementId() + 1);
    var element = $('<div class="grid-stack-item" data-name="'+name+'" data-module="0" data-editable><div class="grid-stack-item-content"></div></div>');
    element.find(".grid-stack-item-content").addGridstackMenu();
    var x = 0;
    var y = getEditorHeight();
    var width = 12;
    var height = 2;
    grid.addWidget(element, x, y, width, height);
}

function getEditorHeight() {
    return blockContent.attr('data-gs-current-height');
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
        content[name]['module'] = (element.attr('data-module') != "") ? element.attr('data-module') : 0;
        content[name]['x'] = element.attr('data-gs-x');
        content[name]['y'] = element.attr('data-gs-y');
        content[name]['width'] = element.attr('data-gs-width');
        content[name]['height'] = element.attr('data-gs-height');
    });

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

// Define out custom image tool
var CustomImageTool = (function(_super) {
    $.extend(CustomImageTool, _super);

    function CustomImageTool() {
        return CustomImageTool.__super__.constructor.apply(this, arguments);
    }

    // Register the tool with ContentTools (in this case we overwrite the
    // default image tool).
    ContentTools.ToolShelf.stow(CustomImageTool, 'image');

    // Set the label and icon we'll use
    CustomImageTool.label = 'Image';
    CustomImageTool.icon = 'image';

    CustomImageTool.canApply = function(element, selection) {
        // So long as there's an image defined we can always insert an image
        return true;
    };

    CustomImageTool.apply = function(element, selection, callback) {

        // First define a function that we can send the custom media manager
        // when an image is ready to insert.
        function _insertImage(url, width, height) {
            // Once the user has selected an image insert it

            // Create the image element
            var image = new ContentEdit.Image({src: url});

            // Insert the image
            var insertAt = CustomImageTool._insertAt(element);
            insertAt[0].parent().attach(image, insertAt[1]);

            // Set the image as having focus
            image.focus();

            // Call the given tool callback
            return callback(true);
        }

        // Make the new function accessible to your iframe
        window.parent.CustomMediaManager = {_insertImage: _insertImage};

        // Hand off to your custom media manager
        //
        // This bit you'll need to figure out for yourself or provide more
        // details about how your media manager works, for example in
        // KCFinder here we open a new window and point it at the KCFinder
        // browse.php script. In your case you may be looking to insert an
        // iframe element and/or set the src for that iframe.
        //
        // When the user uploads/selects an image in your media manager you
        // are ready to call the `_insertImage` function defined above. The
        // function is accessed against the iframe parent using:
        //
        //     window.parent.CustomMediaManager._insertImage(url, width, height);
        //
        
        console.log("OPEN MODAL");

    };

    return CustomImageTool;

})(ContentTools.Tool);