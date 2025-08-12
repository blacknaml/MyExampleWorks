$.extend(true, $.fn.dataTable.defaults, {
    bFilter: false,
    bProcessing: true,
    bRetrieve: true,
    bScrollCollapse: true,
    sDom: "lfrt<'dataTables_footer'<'tb_toolbar'>p>",
    sPaginationType: "bootstrap",
    oLanguage: {
        sLengthMenu: "_MENU_",
        sProcessing: " ",
        sSearch: "Keyword",
        sEmptyTable: "No data",
        sInfo: "View _START_ to _END_ of _TOTAL_ entries",
        sInfoEmpty: ""
    },
    fnPreDrawCallback: function (e) {
        var t = e.aoColumns;
        $(e.nTBody).addClass("overlay");
        for (var n = 0; n < t.length; n++)
            if (t[n].sType == "currency") {
                t[n].fnRender = function (e) {
                    return formatMoney(e.aData[e.iDataColumn])
                };
                t[n].sClass = "right"
            }
        },
        fnDrawCallback: function (e) {
            $(e.nTBody).removeClass("overlay")
        }
    });
$.extend($.fn.dataTableExt.oStdClasses, {
    sWrapper: "dataTables_wrapper form-inline",
    sProcessing: "dataTables_processing icon-loading-hr",
    sTable: "dynamicTable table table-striped table-bordered table-primary table-condensed table-hover dataTable",
    sPaging: "dataTables_paginate paging_"
});
$.fn.dataTableExt.oApi.fnPagingInfo = function (e) {
    return {
        iStart: e._iDisplayStart,
        iEnd: e.fnDisplayEnd(),
        iLength: e._iDisplayLength,
        iTotal: e.fnRecordsTotal(),
        iFilteredTotal: e.fnRecordsDisplay(),
        iPage: Math.ceil(e._iDisplayStart / e._iDisplayLength),
        iTotalPages: Math.ceil(e.fnRecordsDisplay() / e._iDisplayLength)
    }
};
$.extend($.fn.dataTableExt.oPagination, {
    bootstrap: {
        fnInit: function (e, t, n) {
            var r = e.oLanguage.oPaginate;
            var i = function (t) {
                t.preventDefault();
                if (e.oApi._fnPageChange(e, t.data.action)) {
                    n(e)
                }
            };
            $(t).addClass("pagination pagination-small").append("<ul>" + '<li class="prev disabled"><a href="#">← ' + r.sPrevious + "</a></li>" + '<li class="next disabled"><a href="#">' + r.sNext + " → </a></li>" + "</ul>");
            var s = $("a", t);
            $(s[0]).bind("click.DT", {
                action: "previous"
            }, i);
            $(s[1]).bind("click.DT", {
                action: "next"
            }, i)
        },
        fnUpdate: function (e, t) {
            var n = 5;
            var r = e.oInstance.fnPagingInfo();
            var i = e.aanFeatures.p;
            var s, o, u, a, f, l = Math.floor(n / 2);
            if (r.iTotalPages < n) {
                a = 1;
                f = r.iTotalPages
            } else if (r.iPage <= l) {
                a = 1;
                f = n
            } else if (r.iPage >= r.iTotalPages - l) {
                a = r.iTotalPages - n + 1;
                f = r.iTotalPages
            } else {
                a = r.iPage - l + 1;
                f = a + n - 1
            }
            for (s = 0, iLen = i.length; s < iLen; s++) {
                $("li:gt(0)", i[s]).filter(":not(:last)").remove();
                for (o = a; o <= f; o++) {
                    u = o == r.iPage + 1 ? 'class="active"' : "";
                    $("<li " + u + '><a href="#">' + o + "</a></li>").insertBefore($("li:last", i[s])[0]).bind("click", function (n) {
                        n.preventDefault();
                        e._iDisplayStart = (parseInt($("a", this).text(), 10) - 1) * r.iLength;
                        t(e)
                    })
                }
                if (r.iPage === 0) {
                    $("li:first", i[s]).addClass("disabled")
                } else {
                    $("li:first", i[s]).removeClass("disabled")
                } if (r.iPage === r.iTotalPages - 1 || r.iTotalPages === 0) {
                    $("li:last", i[s]).addClass("disabled")
                } else {
                    $("li:last", i[s]).removeClass("disabled")
                }
            }
        }
    },
    lookup: {
        fnInit: function (e, t, n) {
            var r = e.oLanguage.oPaginate;
            var i = function (t) {
                t.preventDefault();
                if (e.oApi._fnPageChange(e, t.data.action)) {
                    n(e)
                }
            };
            $(t).addClass("pagination pagination-mini").append("<ul>" + '<li class="prev disabled"><a href="#" title="Previous"> < </a></li>' + '<li class="next disabled"><a href="#" title="Next"> > </a></li>' + "</ul>");
            var s = $("a", t);
            s.tooltip();
            $(s[0]).bind("click.DT", {
                action: "previous"
            }, i);
            $(s[1]).bind("click.DT", {
                action: "next"
            }, i)
        },
        fnUpdate: function (e, t) {
            var n = 5;
            var r = e.oInstance.fnPagingInfo();
            var i = e.aanFeatures.p;
            var s, o, u, a, f, l = Math.floor(n / 2);
            if (r.iTotalPages < n) {
                a = 1;
                f = r.iTotalPages
            } else if (r.iPage <= l) {
                a = 1;
                f = n
            } else if (r.iPage >= r.iTotalPages - l) {
                a = r.iTotalPages - n + 1;
                f = r.iTotalPages
            } else {
                a = r.iPage - l + 1;
                f = a + n - 1
            }
            for (s = 0, iLen = i.length; s < iLen; s++) {
                $("li:gt(0)", i[s]).filter(":not(:last)").remove();
                if (r.iPage === 0) {
                    $("li:first", i[s]).addClass("disabled")
                } else {
                    $("li:first", i[s]).removeClass("disabled")
                } if (r.iPage === r.iTotalPages - 1 || r.iTotalPages === 0) {
                    $("li:last", i[s]).addClass("disabled")
                } else {
                    $("li:last", i[s]).removeClass("disabled")
                }
            }
        }
    }
});
$.fn.dataTableExt.oApi.fnReloadAjax = function (e, t, n, r) {
    if (typeof t != "undefined" && t != null) {
        e.sAjaxSource = t
    }
    this.oApi._fnProcessingDisplay(e, true);
    var i = this;
    var s = e._iDisplayStart;
    var o = [];
    this.oApi._fnServerParams(e, o);
    e.fnServerData(e.sAjaxSource, o, function (t) {
        i.oApi._fnClearTable(e);
        var o = e.sAjaxDataProp !== "" ? i.oApi._fnGetObjectDataFn(e.sAjaxDataProp)(t) : t;
        for (var u = 0; u < o.length; u++) {
            i.oApi._fnAddData(e, o[u])
        }
        e.aiDisplay = e.aiDisplayMaster.slice();
        i.fnDraw();
        if (typeof r != "undefined" && r === true) {
            e._iDisplayStart = s;
            i.fnDraw(false)
        }
        i.oApi._fnProcessingDisplay(e, false);
        if (typeof n == "function" && n != null) {
            n(e)
        }
    }, e)
};
$.extend($.fn.dataTableExt.oSort, {
    "currency-asc": function (e, t) {
        e = e.replace(/[^\d\.\-]/g, "");
        t = t.replace(/[^\d\.\-]/g, "");
        if (isNaN(e.substring(0, 1))) {
            e = e.substring(1)
        }
        if (isNaN(t.substring(0, 1))) {
            t = t.substring(1)
        }
        e = parseFloat(e);
        t = parseFloat(t);
        return e - t
    },
    "currency-desc": function (e, t) {
        e = e.replace(/[^\d\.\-]/g, "");
        t = t.replace(/[^\d\.\-]/g, "");
        if (isNaN(e.substring(0, 1))) {
            e = e.substring(1)
        }
        if (isNaN(t.substring(0, 1))) {
            t = t.substring(1)
        }
        e = parseFloat(e);
        t = parseFloat(t);
        return t - e
    },
    "lookup-pre": $.fn.dataTableExt.oSort["string-pre"],
    "lookup-asc": $.fn.dataTableExt.oSort["string-asc"],
    "lookup-desc": $.fn.dataTableExt.oSort["string-desc"],
    "select-pre": $.fn.dataTableExt.oSort["string-pre"],
    "select-asc": $.fn.dataTableExt.oSort["string-asc"],
    "select-desc": $.fn.dataTableExt.oSort["string-desc"]
});
$.fn.dataTableExt.oApi.fnGetCommand = function (e) {
    var t = $("li span", e.nTableWrapper);
    return t
};
$.fn.dataTableExt.oApi.fnCommand = function (e, t, n) {
    var r = "tb_" + t;
    var i = $("li span#" + t, e.nTableWrapper).parent();
    if (n === false || n == "disabled") i.addClass("disabled");
    else i.removeClass("disabled")
};
$.fn.dataTableExt.oApi.fnGetSelectedId = function (e) {
    var t = this.fnGetSelectedData()[0];
    return t[0]
};
$.fn.dataTableExt.oApi.fnGetSelectedRows = function (e) {
    return this.$("tr.row_selected")
};
$.fn.dataTableExt.oApi.fnGetSelectedData = function (e) {
    return this._("tr.row_selected")
};
$.fn.dataTableExt.oApi.fnEditableRow = function (e, t) {
    var n = this;
    var r = this.fnGetPosition(t);
    var s = this.fnGetData(r);
    var o = e.aoColumns;
    var u = e.oInit.bEditClick;
    $(t).siblings().each(function () {
        $(this).removeClass("active").removeClass("row_selected").removeClass("ui-state-highlight")
    });
    $(t).addClass("active row_selected ui-state-highlight");
    var a = $(">td", t);
    $(t).siblings().unbind("click");
    for (i = 0; i < o.length; i++) {
        if (o[i].readonly) continue;
        var f = "";
        if ($(a[i]).hasClass("right")) f = "text-align:right;";
        if (o[i].sType == "select") {
            var l = "";
            dts = o[i].dataSource;
            for (j = 0; j < dts.length; j++) {
                l += '<option value="' + (dts[j].id != undefined ? dts[j].id : dts[j].caption) + '">' + dts[j].caption + "</option>"
            }
            a[i].innerHTML = '<select class="dt" style="width:' + o[i].sWidth + ';" name="' + o[i].sName + '" >' + l + "</select>";
            $('select[name="' + o[i].sName + "\"] option:contains('" + s[i] + "')").attr("selected", "selected")
        } else if (o[i].sType == "date") {
            a[i].innerHTML = '<input class="dt date_input" style="width:' + o[i].sWidth + ';" value="' + s[i] + '">';
            $(a[i]).find(".dt").datepicker(dt_params)
        } else a[i].innerHTML = '<input class="dt" type="text" style="width:' + o[i].sWidth + ";" + f + '" value="' + s[i] + '">'
    }
    var c = function (r) {
        for (i = 0; i < o.length; i++) {
            if (o[i].readonly) continue;
            var s = $("input", a[i]);
            var f = $("select", a[i]);
            var l = "",
            c = "";
            if (f.length > 0) {
                l = $(f[0]).val();
                var h = $("option:selected", $(f[0]));
                c = $(h[0]).text()
            }
            if (s.length > 0) {
                c = s[0].value
            }
            n.fnUpdate(c, t, i, false)
        }
        n.fnDraw();
        var p = $(".center .dt_status", e.nTableWrapper);
        p.removeClass("saved").addClass("notsaved");
        if (u) n.fnEditableRow($(r.target).closest("tr").get(0))
    };
$(t).siblings().click(function (e) {
    if ($(this).hasClass("active")) return;
    var t = 0;
    $(this).siblings().each(function () {
        if ($(this).hasClass("active")) t = 1;
        $(this).removeClass("active")
    });
    if (t == 0) return;
    c(e)
});
$(t).find("td input").keypress(function (e) {
    var t = $(e.target).closest("tr");
    if (e.which == 13) {
        c(e);
        t.removeClass("active")
    }
})
};
$.fn.dataTableExt.oApi.fnEditCell = function (e, t) {
    if (t.tagName != "TD") return;
    if ($(t).hasClass("active")) return;
    var n = this;
    var r = this.fnGetPosition(t);
    var i = this.fnGetData(t);
    var s = e.aoColumns[r[1]];
    if (s.readonly) return;
    $(t).css("background", "#e0ffe2").addClass("active");
    console.log("starting...");
    var o = "";
    if ($(t).hasClass("right")) o += "text-align:right;";
    if (s.sType == "select") {
        var u = "";
        dts = s.dataSource;
        for (j = 0; j < dts.length; j++) {
            u += '<option value="' + (t.id != undefined ? dts[j].id : dts[j].caption) + '">' + dts[j].caption + "</option>"
        }
        t.innerHTML = '<select class="dt" style="width:' + s.sWidth + ';" name="' + s.sName + '" >' + u + "</select>";
        $('select[name="' + s.sName + "\"] option:contains('" + i + "')").attr("selected", "selected")
    } else if (s.sType == "date") {
        t.innerHTML = '<input class="dt date_input" style="width:' + s.sWidth + ';" value="' + i + '">';
        $(t).find(".dt").datepicker(dt_params)
    } else t.innerHTML = '<input class="dt" type="text" style="width:' + s.sWidth + ";" + o + '" value="' + i + '">';
    $("input,select", t).focus();
    var a = function (i) {
        $(t).css("background", "transparent").removeClass("active");
        if (s.readonly) return;
        var o = $("input", t);
        var u = $("select", t);
        var a = "",
        f = "";
        console.log("leaving...");
        if (u.length > 0) {
            a = $(u[0]).val();
            var l = $("option:selected", $(u[0]));
            f = $(l[0]).text()
        }
        if (o.length > 0) {
            f = o[0].value
        } else return;
        n.fnUpdate(f, r[0], r[1], false);
        n.fnDraw();
        var c = $(".center .dt_status", e.nTableWrapper);
        c.removeClass("saved").addClass("notsaved")
    };
    $(t).focusout(function (e) {
        a(e)
    })
};
$.fn.dataTableExt.oApi.fnEditRow = function (e) {
    var t = this.fnGetSelectedRows()[0];
    if (!t) return;
    this.fnEditableRow(t)
};
$.fn.dataTableExt.oApi.fnAddRow = function (e, t) {
    var n, r = Array(),
    i = e.aoColumns;
    r[0] = "1";
    for (n = 0; n < i.length; n++) {
        r[n] = ""
    }
    if (t) r[i.length - 1] = t;
    var s = this.fnAddData(r, true);
    var o = this.fnGetNodes(s[0]);
    return o
};
$.fn.dataTableExt.oApi.getSerialize = function (e) {
    var t, n, r = 0;
    var i = new Array;
    var s = e.aoColumns;
    $(e.aoData).each(function () {
        for (r = 0; r < s.length; r++) {
            t = this._aData[r];
            if (s[r].sType == "select") {
                var e = s[r].source;
                var n = Object.prototype.toString.call(e) === "[object Array]" ? false : true;
                for (var o in e) {
                    optVal = n ? o : e[o];
                    if (e[o] == this._aData[r]) t = n ? o : e[o]
                }
        }
        i.push({
            name: s[r].sName + "[]",
            value: t
        })
    }
});
    return i
};
$.fn.dataTableExt.oApi.getSelectedSerialize = function (e) {
    var t, n, r = 0;
    var i = new Array;
    var s = e.aoColumns;
    var o = this.fnGetSelectedData()[0];
    for (r = 0; r < s.length; r++) {
        t = o[r];
        if (s[r].sType == "select") {
            var u = s[r].dataSource;
            for (n = 0; n < u.length; n++) {
                if (u[n].caption == this.data[r]) t = u[n].id
            }
    }
    i.push({
        name: s[r].sName,
        value: t
    })
}
return i
};
$.fn.dataTableExt.oApi.applySearchBox = function (e) {
    var t = $(e.nTableWrapper);
    if (e.oFeatures.bFilter || e.oFeatures.bLengthChange) {
        $("thead tr", t).addClass("page-header")
    } else $("> table", t).addClass("dataTable-noheader");
    var n = this;
    var r = e.oInit.filter_by;
    var i = e.oFeatures.bServerSide;
    if (e.oFeatures.bFilter) {
        var s = e.aoColumns;
        var o, u, a = "";
        u = '<div id="searchbox" class="' + (r ? "input-prepend" : "") + ' input-append">';
        if (r) {
            u += '<select name="category" class="kategori">';
            for (o = 0; o < r.length; o++) {
                if (!i) a = r[o];
                else a = s[r[o]].sName ? s[r[o]].sName : o;
                u += '<option value="' + a + '">' + s[r[o]].sTitle + "</option>"
            }
            u += "</select>"
        }
        var f = "";
        f = '<button type="button" class="btn" id="submit"><i class="icon-search"></i></button>';
        $(e.nTableWrapper).find(".dataTables_filter").html(u + ' <input type="search" style="margin-left: -1px;" placeholder="Search..." name="q" id="search" />' + f + "</div>")
    }
    var l = $(".dataTables_scrollBody", t);
    if (l.length) l.css("border-bottom", "1px solid " + $(".ui-widget-content").css("border-color"));
    else $(".display tbody", t).css("border-bottom", "1px solid " + $(".ui-widget-content").css("border-color"));
    $(".dataTables_scrollBody", t);
    $("#submit", t).click(function () {
        n.fnDraw()
    });
    $("#search", t).keypress(function (e) {
        if (e.keyCode == "13") {
            n.fnDraw();
            return false
        }
    });
    if (!i) {
        $("#search", t).keyup(function (e) {
            var r = $(".kategori", t).val();
            n.fnFilter($(this).val().trim(), r)
        })
    }
    if (e.oInit.oFilter) {
        $(".dataTables_filter", t).html(e.oInit.oFilter).hide();
        $("#cb_search", e.oInit.oFilter).click(function () {
            n.fnDraw()
        })
    }
    console.log("apply search box...")
};
$.fn.dataTableExt.oApi.applyTheme = function (e) {
    var t = this;
    var n = $(e.nTableWrapper);
    t.applySearchBox();
    $("tfoot", n).addClass("page-header");
    if (!e.oInit.bToolbar) return;
    var r = e.oInit.type == "transient" ? 1 : 0;
    var s = e.oInit.oButton;
    var o = e.oInit;
    var u = {
        c: {
            id: "tb_add",
            icon: "icon-plus",
            title: "New"
        },
        u: {
            id: "tb_edit",
            icon: "icon-pencil",
            title: "Edit",
            rowSelection: true
        },
        d: {
            id: "tb_delete",
            icon: "icon-trash",
            title: "Delete",
            rowSelection: true
        },
        r: {
            id: "tb_refresh",
            icon: "icon-refresh",
            title: "Refresh"
        },
        f: {
            id: "tb_search",
            icon: "icon-search",
            title: "Search"
        },
        s: {
            id: "tb_submit",
            icon: "icon-ok",
            title: "Submit"
        },
        p: {
            id: "tb_print",
            icon: "icon-print",
            title: "Print",
            rowSelection: true
        },
        m: {
            id: "tb_read",
            icon: "icon-eye-open",
            title: "Read More",
            rowSelection: true
        }
    };
    var a = n.closest(".widget-block");
    var f = a.attr("data-role");
    if (!f) f = "";
    if (!o.bAddButton) f = f.replace(/c/g, "");
    if (!o.bEditButton) f = f.replace(/u/g, "");
    if (!o.bDeleteButton) f = f.replace(/d/g, "");
    if (!o.bReloadButton) f = f.replace(/r/g, "");
    var l = n.attr("id");
    var c = "";
    var h;
    var p = Array();
    var d = {};
    for (var v in u) {
        d[v] = u[v].id
    }
    var m;
    for (i = 0; i < f.length; i++) {
        h = f[i];
        if (u.hasOwnProperty(h)) {
            m = u[h].icon;
            m += u[h].rowSelection ? " row_selection" : "";
            c += '<button type="button" data-toggle="tooltip" class="btn btn-small ' + u[h].id + '" title="' + u[h].title + '" data-action="' + u[h].id + '"><i class="' + m + '" id="' + u[h].id + '"></i></button>'
        }
    }
    if (r == 1 && e.oInit.sSubmitUrl)
        if (f.indexOf("s") != -1 || f.indexOf("c") != -1 || f.indexOf("u") != -1) c += '<button type="button" class="btn btn-small" data-action="' + u["s"].id + '" title="' + u["s"].title + '"><i class="' + u["s"].icon + '" id="' + u["s"].id + '"></i></button>';
    var g = '<div class="btn-toolbar" style="margin:0;"><div class="btn-group" id="icons_' + l + '">' + c + "</div></div>";
    $(".tb_toolbar", n).append(g);
    c = "";
    for (i = 0; i < s.length; i++) {
        var y = s[i];
        var b = true;
        for (var v in d) {
            if (d[v] == y.id && f.indexOf(v) == -1) {
                b = false
            }
        }
        if (!b) continue;
        m = y.icon ? y.icon : "text_button";
        var w = $("#" + y.id, n);
        if (w.length > 0) {
            if (y.visible === false) w.closest("button").remove();
            if (y.title) w.html(y.title).addClass(m);
            if (y.rowSelection) w.addClass("row_selection");
            else w.removeClass("row_selection");
            continue
        }
        if (y.visible === false) continue;
        m += y.rowSelection ? " row_selection" : "";
        var E = y.visible === false ? "display:none;" : "";
        var S = '<button type="button" style="' + E + '" class="btn btn-small" title="' + y.title + '" data-action="' + y.id + '"><i class="' + m + ' " id="' + y.id + '">' + y.title + "</i></button>";
        c += S
    }
    $(".tb_toolbar .btn-toolbar .btn-group", n).append(c);
    $(".tb_toolbar .btn-toolbar .btn-group button", n).tooltip();
    if (r) $(".center", n).prepend('<div class="dt_status"></div>')
};
$.fn.dataTableExt.oApi.fnCellEditor = function (e) {
    console.log("create cell editor");
    var t = $(e.nTableWrapper);
    var n = this;
    var r = e.oInit.fnCellChange;
    $("table.display tbody", t).unbind("click");
    $("table.display tbody td", t).dblclick(function () {
        if ($(this).hasClass("active")) return;
        console.log("dblclick");
        var t = this;
        var r = n.fnGetPosition(t);
        var i = n.fnGetData(t);
        var s = e.aoColumns[r[1]];
        if (s.readonly) return;
        $(t).css("background", "#e0ffe2").addClass("active");
        var o = "";
        if ($(t).hasClass("right")) o += "text-align:right;";
        if (s.sType == "select") {
            var u = "";
            dts = s.dataSource;
            for (j = 0; j < dts.length; j++) {
                u += '<option value="' + (t.id != undefined ? dts[j].id : dts[j].caption) + '">' + dts[j].caption + "</option>"
            }
            t.innerHTML = '<select class="dt" style="width:' + s.sWidth + ';" name="' + s.sName + '" >' + u + "</select>";
            $('select[name="' + s.sName + "\"] option:contains('" + i + "')").attr("selected", "selected")
        } else if (s.sType == "date") {
            t.innerHTML = '<input class="dt date_input" style="width:' + s.sWidth + ';" value="' + i + '">';
            $(t).find(".dt").datepicker(dt_params)
        } else t.innerHTML = '<input class="dt" type="text" style="width:' + s.sWidth + ";" + o + '" value="' + i + '">'
    });
$("table.display tbody td", t).click(function () {
    if ($(this).hasClass("active")) return;
    var i = null;
    $("table.display tbody td", t).each(function () {
        if ($(this).hasClass("active")) i = this;
        $(this).css("background", "transparent")
    });
    $(this).css("background", "#ffe0fd");
    if (!i) return;
    console.log("out");
    $(i).css("background", "transparent").removeClass("active");
    var s = n.fnGetPosition(i);
    var o = e.aoColumns[s[1]];
    if (o.readonly) return;
    var u = $("input", i);
    var a = $("select", i);
    var f = "",
    l = "";
    console.log("leaving...");
    if (a.length > 0) {
        f = $(a[0]).val();
        var c = $("option:selected", $(a[0]));
        l = $(c[0]).text()
    }
    if (u.length > 0) {
        l = u[0].value
    } else return;
    n.fnUpdate(l, s[0], s[1], false);
    n.fnDraw();
    var h = $(".center .dt_status", e.nTableWrapper);
    h.removeClass("saved").addClass("notsaved");
    if (r) r({
        target: i
    }, l)
})
};
$.fn.dataTableExt.oApi.fnClearSelection = function (e) {
    var t = $(e.nTableWrapper);
    $("tbody tr", t).removeClass("row_selected ui-state-highlight")
};
$.fn.dataTableExt.oApi.fnEditor = function (e, t) {
    var n = this;
    this.addClass("table-editor");
    var t = $.extend({
        icon: "ui-icon-search",
        fnAddRow: function () {},
        fnSaveRow: function () {},
        fnEditRow: function () {},
        fnDeleteRow: function () {}
    }, t);
    var r = $(e.nTableWrapper);
    $(".tb_toolbar .btn-group .btn", r).each(function () {
        if ($("i", this).attr("id") == "tb_edit" || $("i", this).attr("id") == "tb_delete") $(this).remove()
    });
    $("tbody", r).unbind("click");
    $(".dataTable", r).removeClass("table-hover");
    e.oInit.fnButton = function (e) {
        if (e.target == "tb_add") {
            if (t.fnAddRow() === false) return;
            var r = n.fnAddRow(t.action);
            if (t.type === "inline") $("td .btn.edit", r).click();
            return false
        }
    };
    console.log("creating editor ...");
    var s = [];
    var o = e.aoColumns;
    var u = 0;
    for (i = 0; i < o.length; i++) {
        if (!o[i].bVisible) continue;
        if (o[i].readonly) {
            u++;
            continue
        }
        var a = "";
        s[u] = "";
        if (o[i].sClass == "right") a = "text-align:right;";
        if (o[i].sType == "select") {
            var f = "",
            l;
            var c = o[i].source;
            var h = Object.prototype.toString.call(c) === "[object Array]" ? false : true;
            for (var p in c) {
                l = h ? p : c[p];
                f += '<option value="' + l + '">' + c[p] + "</option>"
            }
            s[u] = '<select class="dt editor" name="' + o[i].sName + '" >' + f + "</select>"
        } else if (o[i].sType == "lookup") {
            var d = o[i].source;
            s[u] = '<input name="lookupdt' + n.attr("id") + i + '" class="dt editor lookup_input" data-cls="editor" type="text" style="' + a + '">'
        } else if (o[i].sType == "action") {
            o[i].bSortable = false
        }
        u++
    }
    if (t.type === "inline") {
        n.on("click", "td .btn", function (r) {
            var o = $(this).closest("tr").get(0);
            var u = n.fnGetPosition(o);
            var a = n.fnGetData(u);
            var f = $("td", o);
            var l = 0;
            var c = e.aoColumns;
            if ($(this).hasClass("edit")) {
                if ($(this).hasClass("save")) {
                    for (i = 0; i < c.length; i++) {
                        if (!c[i].bVisible) continue;
                        if (c[i].readonly) {
                            l++;
                            continue
                        }
                        var h = $("input", f[l]);
                        var p = $("select", f[l]);
                        var d = "",
                        v = "";
                        if (p.length > 0) {
                            d = $(p[0]).val();
                            var m = $("option:selected", $(p[0]));
                            v = $(m[0]).text()
                        }
                        if (h.length > 0) {
                            v = h[0].value
                        }
                        if (c[i].sType != "action") a[i] = v;
                        l++
                    }
                    if (t.fnSaveRow({
                        data: a,
                        row: o
                    }) === false) return;
                        $(this).removeClass("icon-save save").addClass("icon-edit").html("Edit");
                        $(o).addClass("warning").removeClass("error");
                        n.fnUpdate(a, o);
                        n.fnDraw();
                        return
                    }
                    $(this).removeClass("icon-edit").addClass("icon-save save").html("Save");
                    $(o).addClass("error").removeClass("warning");
                    for (i = 0; i < c.length; i++) {
                        if (!c[i].bVisible) continue;
                        if (c[i].readonly) {
                            l++;
                            continue
                        }
                        var g = "";
                        if ($(f[l]).hasClass("right")) g = "text-align:right;";
                        if (c[i].sType == "select") {
                            f[l].innerHTML = s[l];
                            $('select[name="' + c[i].sName + "\"] option:contains('" + a[i] + "')", f[l]).attr("selected", "selected").change = c[i].change
                        } else if (c[i].sType == "date") {
                            f[l].innerHTML = '<input type="text" class="dt date_input editor" value="' + a[i] + '">';
                            $(".editor", f[l]).datepicker().change = c[i].change
                        } else if (c[i].sType == "lookup") {
                            var y = c[i].source;
                            f[l].innerHTML = s[l];
                            var b = new Date;
                            var w = "lk_" + b.getTime();
                            $(".editor", f[l]).attr("id", w).val(a[i]);
                            var E = c[i].target;
                            if (E) c[i].change = function (e) {
                                n.fnUpdate(e.data[0], o, e.option.target, false)
                            };
                            $("input", f[l]).createLookUp({
                                oTable: y,
                                idField: 0,
                                textField: 1,
                                fnSelect: c[i].change,
                                multiselect: false,
                                option: {
                                    target: E,
                                    row: o
                                }
                            })
                        } else if (c[i].sType == "action") {} else {
                            f[l].innerHTML = '<input class="dt editor" type="text" style="' + g + '" value="' + a[i] + '">';
                            $(f[l].innerHTML).formatCurrency().change = c[i].change;
                            console.log("change")
                        }
                        l++
                    }
                    if (t.fnEditRow({
                        data: a,
                        row: o
                    }) === false) return
                } else if ($(this).hasClass("delete")) {
                    if (t.fnDeleteRow({
                        data: a
                    }) === false) return;
                        n.fnDeleteRow(o)
                    }
                })
} else if (t.type === "default") n.on("click", "td", function (e) {
    if ($(e.target).hasClass("dataTables_empty")) return;
    console.log("cell clicked");
    var t = this;
    var r = $(this).closest("tr").get(0);
    var i = n.fnGetPosition(this);
    var o = n.fnGetData(this);
    if ($(".editor", this).length > 0) return;
    var u = n.fnSettings().aoColumns[i[2]];
    if (u.readonly) return;
    $(this).addClass("active");
    var a = "";
    if ($(this).hasClass("right")) a += "text-align:right;";
    if (u.sType == "select") {
        var f = "",
        l, c;
        var h = u.source;
        var p = Object.prototype.toString.call(h) === "[object Array]" ? false : true;
        for (var d in h) {
            c = p ? d : h[d];
            l = h[d] == o ? 'selected="selected"' : "";
            f += "<option " + l + ' value="' + c + '">' + h[d] + "</option>"
        }
        t.innerHTML = '<select class="editor" name="' + u.sName + '" >' + f + "</select>";
        $(".editor", this).focus()
    } else if (u.sType == "date") {
        t.innerHTML = '<input type="text" id="dt' + n.attr("id") + i[2] + '" class="editor date_input" value="' + o + '">';
        $(".editor", this).datepicker(dt_params);
        $(".editor", this).focus()
    } else if (u.sType == "lookup") {
        var v = u.source;
        this.innerHTML = s[$(this).index()];
        var m = new Date;
        var g = "lk_" + m.getTime();
        $(".editor", this).attr("id", g).val(o);
        var y = u.target;
        if (y) u.change = function (e) {
            n.fnUpdate(e.data[0], i[0], e.option.target, false)
        };
        var b = u.list === false ? false : true;
        $("input", this).createLookUp({
            oTable: v,
            idField: 0,
            textField: 1,
            bListOnly: b,
            fnSelect: u.change,
            multiselect: false,
            option: {
                target: y,
                row: r
            }
        });
        $("input", this).focus()
    } else if (u.sType == "action") {
        if ($(".delete", this).length) n.fnDeleteRow($(t).parent().get(0))
    } else {
        this.innerHTML = '<input class="dt editor" type="text" style="' + a + '" value="' + o + '">';
        $(".editor", this).get(0).selectionStart = -1
    }
    $(".editor", this).change(function () {
        $(t).addClass("val-change")
    });
    $(".editor", this).blur(function () {
        var e = this.value;
        console.log(e);
        if ($(this).datepicker("widget").is(":visible")) {
            this.selectionStart = -1;
            return
        }
        if ($(this).hasClass("lookup_open")) {
            return
        }
        $(t).removeClass("active");
        if ($(this).is("select")) {
            var r = $("option:selected", this);
            e = $(r[0]).text()
        }
        n.fnUpdate(e, i[0], i[2], false)
    })
});
return this
}