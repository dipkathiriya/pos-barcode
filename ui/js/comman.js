function numonly(id){
    $('#'+id).val($('#'+id).val().replace(/[^0-9]/g, ''));
}


function showMessage(msg, title) {
    PNotify.removeAll();
    var title = (typeof (title) == 'undefined') ? "Success" : title;
    new PNotify({
        title: title,
        text: msg,
        maxOpen: 1,
        animate_speed: 'fast',
        type: 'success'
    });
}

function showError(msg, title) {
    PNotify.removeAll();
    var title = (typeof (title) == 'undefined') ? "Alert" : title;
    new PNotify({
        title: title,
        text: msg,
        type: 'error',
        maxOpen: 1,
        animate_speed: 'fast',
        buttons: {
            closer: true,
            sticker: false, //ugly
            labels: { close: "Fechar", stick: "Manter" }
        }
    });
}