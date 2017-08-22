/**
 * Created by Harry Tang on 9/6/2016.
 */

/**
 * click and copy photo url
 */
$(document).on("click", ".photo-url", function () {
    var temp=$(this).val();
    var obj=$(this);
    obj.select();
    document.execCommand("copy");
    obj.val(obj.data('copy-text'));
    setTimeout(function () {
        obj.val(temp);
    }, 2000);
});

$(document).on("click", ".btn-flickr-delete", function (e) {
    var url = $("#url-load-flickr-delete").val();
    $(this).parent().hide(500);
    $.ajax(url, {data:{id:$(this).data('flickr')}})
        .done(function () {
        })
        .fail(function () {
            alert("error");
        })
        .always(function () {
            //alert("ok");
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
            //alert("ok");
        });
}
loadFlickrPhoto();