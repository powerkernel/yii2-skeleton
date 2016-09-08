/**
 * Created by Harry Tang on 9/6/2016.
 */
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
$(document).on("click", ".photo-url", function () {
    $(this).select();
});
loadFlickrPhoto();