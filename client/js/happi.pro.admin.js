
$.ajaxSetup({
	type: "post",
	timeout: 60000,
	error: function (x, e) {
		if (x.status == 0) {
			$(document).process({ text: "无法连接服务器，可能断网了。请与管理员联系", width: 320 });
		} else if (x.status == 404) {
			$(document).process({ text: "您请求的URL不存在。请与管理员联系", width: 320 });
		} else if (x.status == 500) {
			$(document).process({ text: "服务器错误。请与管理员联系", width: 320 });
		} else if (e == 'parsererror') {
			$(document).process({ text: "请求的JSON格式错误。请与管理员联系", width: 320 });
		} else if (e == 'timeout') {
			$(document).process({ text: "与服务器连接超时。请与管理员联系", width: 320 });
		} else {
			$(document).process({ text: "未知的错误。请与管理员联系", width: 320 });
		}
	}
});

//后台主页管理员/ default.html和页面初始化
$.extend($.reg("happi.pro.admin"), {
	open: function(id) {
		$("#"+id).click();
		return false;
	},
	init: function () {
		//自动适应窗口大小
		$(window).resize(function () {
			var h = 0;
			if ($(".theader").length == 1) h = $(window).height() - $(".theader").height() - $(".content-right > .nav-tab").height() - 8;
			if ($(".mheader").length == 1) h = $(window).height() - $(".mheader").height() - $(".content-right > .nav-tab").height() - 8;
			$(".nav-tab-page").height(h)
			$(".nav-tab-page > .active").height(h);
			$(".content-left").height(h + $(".content-right > .nav-tab").height() + 8);
			$(".nav-tree").height($(".content-left").height() - $(".content-left-nav").height() - 4);
		}).resize();
		//实例一个TAB
		var tab = $(".nav-tab").navTabs();
		window["tab"] = tab;

		//实例树
		$(".nav-tree").navTree(function (me) {
			tab.add(me.id, $(me).html(), $(me).attr("href"));
			$(".nav-tab-right-menu").hide();
			if ($.isIE6()) {
				setTimeout(function () {
					tab.active();
				}, 500);
			}
			if ($(".nav-tree").attr('mobile') == '1') $(".nav-tree").parent().find('i').click();
			return false;
		}).find("> a").first().click();
		this.tabopen();

		//初始化TAB的右键菜单事件
		$(".nav-tab-right-menu a").click(function () {
			switch (this.className) {
				case "close_current": tab.current.find("i").click(); break;
				case "close_all": tab.find("li i").click(); break;
				case "refresh":
					var id = tab.current.attr("id").replace("Tab", "");
					tab.refresh(id, true);
					break;
				case "cancel": break;
			};
			$(".nav-tab-right-menu").hide();
			return false;
		});

		//初始化默认页
		var url = unescape($.getUrlParam("url").trim());
		if (url.length > 1) {
			if ($('a[href="' + url + '"]').length > 0)
				$('a[href="' + url + '"]').first().click();
			else tab.add("DefPage", 'Page', url);
		}

		$(document).bind('keydown', 'esc', function (evt) { tab.close(); return false; });
		$(document).bind('keydown', 'f5', function (evt) { tab.refresh(null, true); return false; });

		$(".content-left-nav i").click(function(){
			if (!$(".content-left:first").hasClass("hide")) {
				$(".content-left:first").addClass("hide");
				$(".content-left-block").removeClass("hide");
				$(".content-right").removeClass("ml170").addClass("ml20");
			}
		});
		$(".content-left-block").click(function(){
			if ($(".content-left:first").hasClass("hide")) {
				$(".content-left:first").removeClass("hide");
				$(".content-left-block").addClass("hide");
				$(".content-right").removeClass("ml20").addClass("ml170");
			}
		});
	},
	delselect: function () {
		//选中时删除按钮上显示选择记录数
		$("#chkAll,.chkAll").click(function () {
			var obj = $(this).parent().parent().parent();
			if (this.checked) {
				obj.find("td input[type='checkbox']").attr("checked", true);
				obj.find("tr:gt(0)").addClass("active");
			} else {
				obj.find("td input[type='checkbox']").attr("checked", false);
				obj.find("tr:gt(0)").removeClass("active");
			}
			$(".btnDelete em").text("(" + obj.find(":checked:not('#chkAll,.chkAll')").length + ")");
		});
		$(".gridview tr").click(function () {
			var obj = $(this).find(':checkbox:not("#chkAll,.chkAll")').first().get(0);
			if (!obj) return;
			var chk = obj.checked;
			obj.checked = !chk;
			if (obj.checked) $(this).addClass("active"); else $(this).removeClass("active");
			$(".btnDelete em").text("(" + $(this).parent().find(":checked:not('#chkAll,.chkAll')").length + ")");
		});
		$('.gridview :checkbox:not("#chkAll,.chkAll")').click(function () {
			var chk = this.checked;
			this.checked = !chk;
			$(".btnDelete em").text("(" + $(".gridview :checked:not('#chkAll,.chkAll')").length + ")");
		});
	},
	tabopen: function () {
		//所有带.TAB开放的类都在TAB里打开
		$(".tab_open").click(function () {
			(top.tab || window["tab"]).add(this.id, $(this).html(), $(this).attr("href"));
			return false;
		});
	},
	iframeinit: function () {
		$(document).ready(function () {
			//TAB右键菜单
			$(document).click(function () {
				$(top.document).find(".nav-tab-right-menu").hide();
			}).click();
			//F5刷新当前页
			top.$(document).bind('keydown', 'f5', function (evt) { top.tab.refresh(null, true); return false; });
		});
	},
	initDate: function (params, obj) {
		//初始化时间控件
		params = params || {};
		params["readOnly"] = true;
		(obj || $(".Wdate")).each(function () {
			if ($(this).hasClass("Wdate")) params["dateFmt"] = 'yyyy-MM-dd';
			if ($(this).hasClass("WdateY")) params["dateFmt"] = 'yyyy';
			if ($(this).hasClass("WdateYM")) params["dateFmt"] = 'yyyyMM';
			if ($(this).hasClass("WdateALL")) params["dateFmt"] = 'yyyy-MM-dd HH:mm:ss';

			$(this).focus(function () {
				params["el"] = this.id;
				WdatePicker(params);
			}).click(function () {
				params["el"] = this.id;
				WdatePicker(params);
			}).blur(function () {
				if ($(this).val() == $(this).attr('default')) $(this).val("").removeClass("text_gray");
			});
		});
	},
	initUserControl: function() {
		$("body").userControl({ url: '/admin/sys/user_control__' });
	},
	pageLoad: function (fixTH) {
		//页面加載
		fixTH = typeof fixTH == 'boolean' ? fixTH : false;
		$(document).ready(function () {
			happi.pro.admin.tabopen();
			$(".table-search :text").textTip();
			if (fixTH) $(".gridview").fixTH();
			happi.pro.admin.initDate();
			$(".input_cbo select").change(function () {
				$(this).prev().text($(this).find("option:selected").text());
			}).change();
			happi.pro.admin.initUserControl();
		});
	}
});


