/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

/**
 * click and copy photo url
 */
$(document).on("click", ".photo-url", function () {
    var temp = $(this).val();
    var obj = $(this);
    var modal = $("#" + obj.data('modal-id'));
    obj.select();
    document.execCommand("copy");
    obj.val(obj.data('copy-text'));
    setTimeout(function () {
        obj.val(temp);
        modal.modal('toggle');
    }, 1000);
});

$(document).on("click", ".btn-flickr-delete", function (e) {
    var url = $("#url-load-flickr-delete").val();
    $(this).parent().hide(500);
    $.ajax(url, {data: {id: $(this).data('flickr')}})
        .done(function () {
        })
        .fail(function () {
            alert("error");
        })
        .always(function () {
        });
    e.preventDefault();
});

function loadFlickrPhoto() {
    var url = $("#url-load-flickr-photo").val();
    $.ajax(url, {})
        .done(function (data) {
            if (data) {
                $("#flickr-photos-container").html(data);
            }
        })
        .fail(function () {
            alert("error");
        })
        .always(function () {
        });
}

loadFlickrPhoto();
