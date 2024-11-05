var datatable;

$(document).ready(function () {
    $("body").on("click", ".cls-add", function () {
        winform(urlcreate, {}, "Tambah Parameter");
    });

    $("body").on("click", ".cls-button-edit", function () {
        winform(urledit, { id: $(this).data("id") }, "Ubah Parameter");
    });

    $("body").on("click", ".cls-button-delete", function () {
        onbtndelete(this);
    });

    $("body").on("click", "#aktif", function () {
        onbtnaktif(this);
    });

    $("body").on("click", "#is_number", function () {
        onbtnIs_number(this);
    });

    setDatatable();
});

function setDatatable() {
    datatable = $("#datatable")
        .on("preXhr.dt", function (e, settings, processing) {
            KTApp.block(".cls-content-data", {
                overlayColor: "#000000",
                type: "v2",
                state: "primary",
                message: "Sedang proses, silahkan tunggu ...",
            });
        })
        .on("xhr.dt", function (e, settings, json, xhr) {
            KTApp.unblock(".cls-content-data");
        })
        .DataTable({
            serverSide: true,
            processing: true,
            search: {
                caseInsensitive: true,
            },
            searchHighlight: true,
            ajax: urldatatable,
            columns: [
                { data: null, orderable: false, searchable: false },
                // { data: "menu", name: "menu", searchable: true },
                { data: "submenu", name: "submenu", searchable: true },
                { data: "tipe", name: "tipe", searchable: true },
                {
                    data: "tabelsumber",
                    name: "tabelsumber",
                    searchable: false,
                    orderable: false,
                },
                {
                    data: "standarvalue",
                    name: "standarvalue",
                    searchable: false,
                    orderable: false,
                },
                {
                    data: "check",
                    name: "check",
                    orderable: false,
                    searchable: false,
                },
                { data: "action", orderable: false, searchable: false },
            ],
            drawCallback: function (settings) {
                var info = datatable.page.info();
                $('[data-toggle="tooltip"]').tooltip();
                datatable
                    .column(0, { search: "applied", order: "applied" })
                    .nodes()
                    .each(function (cell, i) {
                        cell.innerHTML = info.start + i + 1;
                    });
            },
        });
}

function onbtndelete(element) {
    swal.fire({
        title: "Pemberitahuan",
        text:
            "Yakin hapus data Parameter " + $(element).data("parameter") + " ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus data",
        cancelButtonText: "Tidak",
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                url: urldelete,
                data: { id: $(element).data("id") },
                type: "post",
                dataType: "json",
                beforeSend: function () {
                    KTApp.block(".cls-content-data", {
                        overlayColor: "#000000",
                        type: "v2",
                        state: "primary",
                        message: "Sedang proses, silahkan tunggu ...",
                    });
                },
                success: function (data) {
                    KTApp.unblock(".cls-content-data");

                    swal.fire({
                        title: data.title,
                        html: data.msg,
                        type: data.flag,

                        buttonsStyling: false,

                        confirmButtonText:
                            "<i class='flaticon2-checkmark'></i> OK",
                        confirmButtonClass: "btn btn-default",
                    });

                    if (data.flag == "success") {
                        datatable.ajax.reload(null, false);
                    }
                },
                error: function (jqXHR, exception) {
                    KTApp.unblock(".cls-content-data");
                    var msgerror = "";
                    if (jqXHR.status === 0) {
                        msgerror = "jaringan tidak terkoneksi.";
                    } else if (jqXHR.status == 404) {
                        msgerror = "Halaman tidak ditemukan. [404]";
                    } else if (jqXHR.status == 500) {
                        msgerror = "Internal Server Error [500].";
                    } else if (exception === "parsererror") {
                        msgerror = "Requested JSON parse gagal.";
                    } else if (exception === "timeout") {
                        msgerror = "RTO.";
                    } else if (exception === "abort") {
                        msgerror = "Gagal request ajax.";
                    } else {
                        msgerror = "Error.\n" + jqXHR.responseText;
                    }
                    swal.fire({
                        title: "Error System",
                        html: msgerror + ", coba ulangi kembali !!!",
                        type: "error",

                        buttonsStyling: false,

                        confirmButtonText:
                            "<i class='flaticon2-checkmark'></i> OK",
                        confirmButtonClass: "btn btn-default",
                    });
                },
            });
        }
    });
}

function onbtnaktif(element) {
    swal.fire({
        title: "Pemberitahuan",
        text:
            $(element).data("original-title") +
            " " +
            $(element).data("nama") +
            " ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya",
        cancelButtonText: "Tidak",
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                url: urlaktif,
                data: {
                    id: $(element).data("id"),
                    aktif: $(element).data("aktif"),
                },
                type: "post",
                dataType: "json",
                beforeSend: function () {
                    KTApp.block(".cls-content-data", {
                        overlayColor: "#000000",
                        type: "v2",
                        state: "primary",
                        message: "Sedang proses, silahkan tunggu ...",
                    });
                },
                success: function (data) {
                    KTApp.unblock(".cls-content-data");

                    swal.fire({
                        title: data.title,
                        html: data.msg,
                        type: data.flag,

                        buttonsStyling: false,

                        confirmButtonText:
                            "<i class='flaticon2-checkmark'></i> OK",
                        confirmButtonClass: "btn btn-default",
                    });

                    if (data.flag == "success") {
                        datatable.ajax.reload(null, false);
                    }
                },
                error: function (jqXHR, exception) {
                    KTApp.unblock(".cls-content-data");
                    var msgerror = "";
                    if (jqXHR.status === 0) {
                        msgerror = "jaringan tidak terkoneksi.";
                    } else if (jqXHR.status == 404) {
                        msgerror = "Halaman tidak ditemukan. [404]";
                    } else if (jqXHR.status == 500) {
                        msgerror = "Internal Server Error [500].";
                    } else if (exception === "parsererror") {
                        msgerror = "Requested JSON parse gagal.";
                    } else if (exception === "timeout") {
                        msgerror = "RTO.";
                    } else if (exception === "abort") {
                        msgerror = "Gagal request ajax.";
                    } else {
                        msgerror = "Error.\n" + jqXHR.responseText;
                    }
                    swal.fire({
                        title: "Error System",
                        html: msgerror + ", coba ulangi kembali !!!",
                        type: "error",

                        buttonsStyling: false,

                        confirmButtonText:
                            "<i class='flaticon2-checkmark'></i> OK",
                        confirmButtonClass: "btn btn-default",
                    });
                },
            });
        } else {
            if ($(element).data("aktif") == 0) {
                $(element).prop("checked", true);
            } else {
                $(element).prop("checked", false);
            }
        }
    });
}

function onbtnIs_number(element) {
    swal.fire({
        title: "Pemberitahuan",
        text:
            $(element).data("original-title") +
            " " +
            $(element).data("nama") +
            " ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya",
        cancelButtonText: "Tidak",
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                url: urlnumber,
                data: {
                    id: $(element).data("id"),
                    is_number: $(element).data("is_number"),
                },
                type: "post",
                dataType: "json",
                beforeSend: function () {
                    KTApp.block(".cls-content-data", {
                        overlayColor: "#000000",
                        type: "v2",
                        state: "primary",
                        message: "Sedang proses, silahkan tunggu ...",
                    });
                },
                success: function (data) {
                    KTApp.unblock(".cls-content-data");

                    swal.fire({
                        title: data.title,
                        html: data.msg,
                        type: data.flag,

                        buttonsStyling: false,

                        confirmButtonText:
                            "<i class='flaticon2-checkmark'></i> OK",
                        confirmButtonClass: "btn btn-default",
                    });

                    if (data.flag == "success") {
                        datatable.ajax.reload(null, false);
                    }
                },
                error: function (jqXHR, exception) {
                    KTApp.unblock(".cls-content-data");
                    var msgerror = "";
                    if (jqXHR.status === 0) {
                        msgerror = "jaringan tidak terkoneksi.";
                    } else if (jqXHR.status == 404) {
                        msgerror = "Halaman tidak ditemukan. [404]";
                    } else if (jqXHR.status == 500) {
                        msgerror = "Internal Server Error [500].";
                    } else if (exception === "parsererror") {
                        msgerror = "Requested JSON parse gagal.";
                    } else if (exception === "timeout") {
                        msgerror = "RTO.";
                    } else if (exception === "abort") {
                        msgerror = "Gagal request ajax.";
                    } else {
                        msgerror = "Error.\n" + jqXHR.responseText;
                    }
                    swal.fire({
                        title: "Error System",
                        html: msgerror + ", coba ulangi kembali !!!",
                        type: "error",

                        buttonsStyling: false,

                        confirmButtonText:
                            "<i class='flaticon2-checkmark'></i> OK",
                        confirmButtonClass: "btn btn-default",
                    });
                },
            });
        } else {
            if ($(element).data("is_number") == 0) {
                $(element).prop("checked", true);
            } else {
                $(element).prop("checked", false);
            }
        }
    });
}
