jQuery(document).ready(function () {

    $(".datepicker").datepicker({
        format: "dd-mm-yyyy",
        startView: "year",
        autoclose: true
    });

    $(".avatar").on('click', function () {
        bootbox.dialog({
            size: "large",
            title: 'Pilih gambar untuk avatarmu',
            message: $('.user-avatar').parent().html()
        });
        $(".input-avatar").change(function() {
            this.form.submit();
        });
    });

    $(".add-account").on('click', function () {
        bootbox.dialog({
            size: "medium",
            title: 'Tautkan akun sosial media lainnya',
            message: $('.user-link').parent().html()
        });
    });

});