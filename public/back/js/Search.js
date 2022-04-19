function ajaxSearch(text) {
    $.ajax(
        {
            url: "",
            type: "GET",
            data: {
                'text': text,
            },
            success: function (data) {
                $("#body-list").html(data.content);
            }
        }
    )
}