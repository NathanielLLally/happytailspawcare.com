jQuery(document).ready(function ($) {

    var existing = $("#sltk_custom_images").val();

    if (existing) {
        existing.split(",").forEach(function (url) {
            $("#sltk_images_preview").append(
                "<li data-url='" + url + "'><img src='" + url + "' /><button type='button' class='delete_images_button'>Delete</button></li>"
            );
        });
    }

    $("#sltk_images_preview").on("click", ".delete_images_button", function () {
        var li = $(this).closest("li");
        var url = li.data("url");
        li.remove();

        var input = $("#sltk_custom_images");
        var updated = input.val().replace(url + ",", "").replace(url, "");
        input.val(updated);
    });

    $("#sltk_images_preview").sortable({
        update: function () {
            var urls = [];
            $("#sltk_images_preview li").each(function () {
                urls.push($(this).data("url"));
            });
            $("#sltk_custom_images").val(urls.join(","));
        }
    });

    $("#sltk_select_images_btn").on("click", function (e) {
        e.preventDefault();

        var frame = wp.media({
            title: "Select Pictures",
            button: { text: "Use These Pictures" },
            multiple: true
        });

        frame.on("select", function () {
            var selection = frame.state().get("selection");
            var images = [];

            var existing = $("#sltk_custom_images").val();
            if (existing) { images = existing.split(","); }

            selection.each(function (attachment) {
                var url = attachment.get("url");
                images.push(url);

                $("#sltk_images_preview").append(
                    "<li data-url='" + url + "'><img src='" + url + "' /><button type='button' class='delete_images_button'>Delete</button></li>"
                );
            });

            $("#sltk_custom_images").val(images.join(","));
        });

        frame.open();
    });

});
