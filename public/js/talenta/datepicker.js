var KTBootstrapDatepicker = function() {
    var t;
    t = KTUtil.isRTL() ? {
        leftArrow: '<i class="la la-angle-right"></i>',
        rightArrow: '<i class="la la-angle-left"></i>'
    } : {
        leftArrow: '<i class="la la-angle-left"></i>',
        rightArrow: '<i class="la la-angle-right"></i>'
    };
    return {
        init: function() {
            $("#kt_datepicker_1, #kt_datepicker_1_validate").datepicker({
                rtl: KTUtil.isRTL(),
                format: 'dd/mm/yyyy',
                todayHighlight: !0,
                orientation: "bottom left",
                templates: t
            }), $("#kt_datepicker_1_modal").datepicker({
                rtl: KTUtil.isRTL(),
                format: 'dd/mm/yyyy',
                todayHighlight: !0,
                orientation: "bottom left",
                templates: t
            }), $("#kt_datepicker_2, #kt_datepicker_2_validate").datepicker({
                rtl: KTUtil.isRTL(),
                format: 'dd/mm/yyyy',
                todayHighlight: !0,
                orientation: "bottom left",
                templates: t
            }), $("#kt_datepicker_2_modal").datepicker({
                rtl: KTUtil.isRTL(),
                format: 'dd/mm/yyyy',
                todayHighlight: !0,
                orientation: "bottom left",
                templates: t
            }), $("#kt_datepicker_3, #kt_datepicker_3_validate").datepicker({
                rtl: KTUtil.isRTL(),
                format: 'dd/mm/yyyy',
                todayBtn: "linked",
                clearBtn: !0,
                todayHighlight: !0,
                templates: t
            }), $("#kt_datepicker_3_modal").datepicker({
                rtl: KTUtil.isRTL(),
                format: 'dd/mm/yyyy',
                todayBtn: "linked",
                clearBtn: !0,
                todayHighlight: !0,
                templates: t
            }), $("#kt_datepicker_4_1").datepicker({
                rtl: KTUtil.isRTL(),
                format: 'dd/mm/yyyy',
                orientation: "top left",
                todayHighlight: !0,
                templates: t
            }), $("#kt_datepicker_4_2").datepicker({
                rtl: KTUtil.isRTL(),
                format: 'dd/mm/yyyy',
                orientation: "top right",
                todayHighlight: !0,
                templates: t
            }), $("#kt_datepicker_4_3").datepicker({
                rtl: KTUtil.isRTL(),
                format: 'dd/mm/yyyy',
                orientation: "bottom left",
                todayHighlight: !0,
                templates: t
            }), $("#kt_datepicker_4_4").datepicker({
                rtl: KTUtil.isRTL(),
                format: 'dd/mm/yyyy',
                orientation: "bottom right",
                todayHighlight: !0,
                templates: t
            }), $("#kt_datepicker_5").datepicker({
                rtl: KTUtil.isRTL(),
                format: 'dd/mm/yyyy',
                todayHighlight: !0,
                templates: t
            }), $("#kt_datepicker_6").datepicker({
                rtl: KTUtil.isRTL(),
                format: 'dd/mm/yyyy',
                todayHighlight: !0,
                templates: t
            }), $(".kt_datepicker_years").datepicker({
                rtl: KTUtil.isRTL(),
                format: 'yyyy',
                todayBtn: "linked",
                clearBtn: !0,
                todayHighlight: !0,
                templates: t
            })
        }
    }
}();
jQuery(document).ready(function() {
    KTBootstrapDatepicker.init()
});