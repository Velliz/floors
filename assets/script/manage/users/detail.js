jQuery(document).ready(function () {

    var permissions = $(".permissions");

    var auth = $("#authorization").change(function () {
        var appId = $(this).val();
        $.ajax({
            url: 'api/authorization/' + $("#userid").val(),
            type: 'POST',
            dataType: 'json',
            data: {
                appid: appId
            },
            error: function () {
                bootbox.dialog({
                    size: "small",
                    title: 'Error',
                    message: 'Error when sending request to server. Try again later.'
                });
            },
            success: function (data) {
                var options = '';
                $(data.data.Permission).each(function (i, v) {
                    if (v !== null) {
                        options += "<tr>";
                        options += "<td>" + v.pcode + "</td>";
                        options += "<td>" + v.pname + "</td>";
                        options += "<td>" + v.expired + "</td>";
                        options += "<td><a class='btn btn-xs btn-danger' href='authorization/delete/" + v.id + "'>Delete</a></td>";
                        options += "</tr>";
                    }
                });

                permissions.html(options);
            }
        });
    });

    auth.trigger("change");

});