
var editor
window.addEventListener('load', function() {
    editor = ContentTools.EditorApp.get();
    editor.init('*[data-editable]', 'data-name');
    editor._ignition.unmount();
    editor.revert = function() {
        this.revertToSnapshot(this.history.goTo(0), false);
        return true;
    }
});

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
        $("#revertChanges, #saveChanges").hide();
        $("#changePage").show();
    });
}

function saveChanges() {
    editor._ignition.confirm();
    editor._ignition.unmount();
    $("#revertChanges, #saveChanges").hide();
    $("#changePage").show();

    swal({
        title: 'Pagina opgeslagen',
        type: 'success',
        timer: 2000
    });
}


function changePage() {
    editor._ignition.edit();
    editor._ignition.unmount();
    $("#revertChanges, #saveChanges").show();
    $("#changePage").hide();
}