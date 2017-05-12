jQuery(document).ready(function () {

    $(".select2").select2();

    $(".datepicker").datepicker({
        format: "dd-mm-yyyy",
        startView: "year",
        autoclose: true,
    });

    $(".timepicker").timepicker({
        template: 'modal',
        maxHours: 24,
        showMeridian: false,
        defaultTime: false
    });

    var appAuth = $(".app-auth");

    var appSelect = $(".app-code").on('change', function(){
        var appId = $(this).val();
        $.ajax({
            url: 'api/permission',
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
                        options += '<option value="' + v.id + '">' + v.pname + ' [' + v.pcode + ']</option>';
                    }
                });

                appAuth.html(options);
                appAuth.select2();
            }
        });
    });

    appSelect.trigger("change");

});