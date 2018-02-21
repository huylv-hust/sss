// JavaScript Document

var utility = {
    setCookie: function (cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toGMTString();
        document.cookie = cname + "=" + cvalue + "; " + expires;
    },
    setCookieRedirect: function (url, start_time, name_cookie) {
        $.post(url + 'ajax/common/setcookie',
            {
                'start_time': start_time,
                'name_cookie': name_cookie
            },
            function (data) {
            }
        );
    },
    getCookie: function (cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ')
                c = c.substring(1);
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    },
    checkCookie: function (cname) {
        var name = utility.getCookie(cname);
        if (name != "") {
            return name;
        } else {
            return false;
        }
    },
    zen2han: function (e) {
        var str = e.value;
        str = str.replace(/[Ａ-Ｚａ-ｚ０-９－！”＃＄％＆’（）＝＜＞，．？＿［］｛｝＠＾～￥]/g, function (s) {
            return String.fromCharCode(s.charCodeAt(0) - 65248);
        });

        var han = "ｧｨｩｪｫｬｭｮｯ､｡ｰ｢｣ﾞﾟ";
        txt = "ァィゥェォャュョッ、。ー「」　　";
        var result = "";
        for (i=0; i<str.length; i++) {
            c = str.charAt(i); n = txt.indexOf(c,0);
            if (n >= 0) c = han.charAt(n);
            result += c;
        }
        e.value = result;
    },
    han2zen: function (e) {
        var str = e.value;
        str = utility.convertKanaToTwoByte(str);
        str = str.replace(/[!"#$%&'()*+,\-.\/0-9:;<=>?@A-Z\[\\\]^_`a-z{|}~]/g, function (s) {
            return String.fromCharCode(s.charCodeAt(0) + 0xFEE0);
        });

        e.value = str;
    },
    createKanaMap: function (properties, values) {
        var kanaMap = {};
        // 念のため文字数が同じかどうかをチェックする(ちゃんとマッピングできるか)
        if (properties.length === values.length) {
            for (var i = 0, len = properties.length; i < len; i++) {
                var property = properties.charCodeAt(i),
                    value = values.charCodeAt(i);
                kanaMap[property] = value;
            }
        }
        return kanaMap;
    },
    m: function () {
        return utility.createKanaMap(
            'アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲンァィゥェォッャュョ',
            'ｱｲｳｴｵｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄﾅﾆﾇﾈﾉﾊﾋﾌﾍﾎﾏﾐﾑﾒﾓﾔﾕﾖﾗﾘﾙﾚﾛﾜｦﾝｧｨｩｪｫｯｬｭｮ'
        );
    },
    mm: function () {
        return utility.createKanaMap(
            'ｱｲｳｴｵｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄﾅﾆﾇﾈﾉﾊﾋﾌﾍﾎﾏﾐﾑﾒﾓﾔﾕﾖﾗﾘﾙﾚﾛﾜｦﾝｧｨｩｪｫｯｬｭｮ',
            'アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲンァィゥェォッャュョ'
        );
    },
    g: function () {
        return utility.createKanaMap(
            'ガギグゲゴザジズゼゾダヂヅデドバビブベボ',
            'ｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄﾊﾋﾌﾍﾎ'
        );
    },
    gg: function () {
        return utility.createKanaMap(
            'ｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄﾊﾋﾌﾍﾎ',
            'ガギグゲゴザジズゼゾダヂヅデドバビブベボ'
        );
    },
    p: function () {
        return utility.createKanaMap(
            'パピプペポ',
            'ﾊﾋﾌﾍﾎ'
        );
    },
    pp: function () {
        return utility.createKanaMap(
            'ﾊﾋﾌﾍﾎ',
            'パピプペポ'
        );
    },
    gMark: 'ﾞ'.charCodeAt(0),
    pMark: 'ﾟ'.charCodeAt(0),
    convertKanaToTwoByte: function (str) {
        var gg = utility.gg();
        var pp = utility.pp();
        var mm = utility.mm();
        for (var i = 0, len = str.length; i < len; i++) {
            if (str.charCodeAt(i) === utility.gMark || str.charCodeAt(i) === utility.pMark) {
                if (str.charCodeAt(i) === utility.gMark && gg[str.charCodeAt(i - 1)]) {
                    str = str.replace(str[i - 1], String.fromCharCode(gg[str.charCodeAt(i - 1)]))
                        .replace(str[i], '');
                }
                else if (str.charCodeAt(i) === utility.pMark && pp[str.charCodeAt(i - 1)]) {
                    str = str.replace(str[i - 1], String.fromCharCode(pp[str.charCodeAt(i - 1)]))
                        .replace(str[i], '');
                }
                else {
                    break;
                }
                i--;
                len = str.length;
            }
            else {
                if (mm[str.charCodeAt(i)] && str.charCodeAt(i + 1) !== utility.gMark && str.charCodeAt(i + 1) !== utility.pMark) {
                    str = str.replace(str[i], String.fromCharCode(mm[str.charCodeAt(i)]));
                }
            }
        }

        return str;
    },
    convertKanaToOneByte: function (e) {
        var str = e.value;
        var g = utility.g();
        var p = utility.p();
        var m = utility.m();
        for (var i = 0, len = str.length; i < len; i++) {
            // 濁音もしくは半濁音文字
            if (g.hasOwnProperty(str.charCodeAt(i)) || p.hasOwnProperty(str.charCodeAt(i))) {
                // 濁音
                if (g[str.charCodeAt(i)]) {
                    str = str.replace(str[i], String.fromCharCode(g[str.charCodeAt(i)]) + String.fromCharCode(utility.gMark));
                }
                // 半濁音
                else if (p[str.charCodeAt(i)]) {
                    str = str.replace(str[i], String.fromCharCode(p[str.charCodeAt(i)]) + String.fromCharCode(utility.pMark));
                }
                else {
                    break;
                }
                // 文字列数が増加するため調整
                i++;
                len = str.length;
            }
            else {
                if (m[str.charCodeAt(i)]) {
                    str = str.replace(str[i], String.fromCharCode(m[str.charCodeAt(i)]));
                }
            }
        }
        //return str;
        e.value = str;
    },
    getKey: function (app_id, auth_host, date, search) {
        $.ajax({
            type: "GET",
            url: "https://" + auth_host + "/v1/auth?appid=" + app_id + "&date=" + date,
            dataType: 'jsonp',
            async: true,
            crossDomain: true,
            success: function (data) {
                if (data.status = "success") {
                    utility.setCookie('key', data.key);
                }
                search();
            },
            error: function (data) {
                search();
            }
        });
    },

    toKatakanaCase: function (e) {
        var str = e;
        var i, c, a = [];
        for (i = str.length - 1; 0 <= i; i--) {
            c = str.charCodeAt(i);
            a[i] = (0x3041 <= c && c <= 0x3096) ? c + 0x0060 : c;
        }
        var string = String.fromCharCode.apply(null, a);
        return string;
    },

    toHankakuCase: function (e) {
        var str = e.value;
        var str = utility.toKatakanaCase(str);

        var i, f, c, m, a = [];

        m =
        {
            0x30A1: 0xFF67, 0x30A3: 0xFF68, 0x30A5: 0xFF69, 0x30A7: 0xFF6A, 0x30A9: 0xFF6B,
            0x30FC: 0xFF70, 0x30A2: 0xFF71, 0x30A4: 0xFF72, 0x30A6: 0xFF73, 0x30A8: 0xFF74,
            0x30AA: 0xFF75, 0x30AB: 0xFF76, 0x30AD: 0xFF77, 0x30AF: 0xFF78, 0x30B1: 0xFF79,
            0x30B3: 0xFF7A, 0x30B5: 0xFF7B, 0x30B7: 0xFF7C, 0x30B9: 0xFF7D, 0x30BB: 0xFF7E,
            0x30BD: 0xFF7F, 0x30BF: 0xFF80, 0x30C1: 0xFF81, 0x30C4: 0xFF82, 0x30C6: 0xFF83,
            0x30C8: 0xFF84, 0x30CA: 0xFF85, 0x30CB: 0xFF86, 0x30CC: 0xFF87, 0x30CD: 0xFF88,
            0x30CE: 0xFF89, 0x30CF: 0xFF8A, 0x30D2: 0xFF8B, 0x30D5: 0xFF8C, 0x30D8: 0xFF8D,
            0x30DB: 0xFF8E, 0x30DE: 0xFF8F, 0x30DF: 0xFF90, 0x30E0: 0xFF91, 0x30E1: 0xFF92,
            0x30E2: 0xFF93, 0x30E4: 0xFF94, 0x30E6: 0xFF95, 0x30E8: 0xFF95, 0x30E9: 0xFF97,
            0x30EA: 0xFF98, 0x30EB: 0xFF99, 0x30EC: 0xFF9A, 0x30ED: 0xFF9B, 0x30EF: 0xFF9C,
            0x30F2: 0xFF66, 0x30F3: 0xFF9D, 0x3000: 0x0030
        };

        for (i = 0, f = str.length; i < f; i++) {
            c = str.charCodeAt(i);
            if (c == '12288') c = '32';
            switch (true) {
                case (c in m):
                    a.push(m[c]);
                    break;
                case (0xFF21 <= c && c <= 0xFF5E):
                    a.push(c - 0xFEE0);
                    break;
                // ガ－ド
                case (0x30AB <= c && c <= 0x30C9):
                    a.push(m[c - 1], 0xFF9E);
                    break;
                // ハバパ－ホボポの濁点と半濁点
                case (0x30CF <= c && c <= 0x30DD):
                    a.push(m[c - c % 3], [0xFF9E, 0xFF9F][c % 3 - 1]);
                    break;
                default:
                    a.push(c);
                    break;
            }
        }

        var string = String.fromCharCode.apply(null, a);
        e.value = string;
    },
	showLoading: function(msg)
	{
		if (msg != undefined) {
			$('div.please-wait span.loading-text').html(msg);
		}
		$('div.please-wait').show();
	},
	hideLoading: function()
	{
		$('div.please-wait').hide();
	},
	wareki : function(year)
	{
		if (year < 1989) {
			return '昭和' + (year - 1925);
		} else {
			return '平成' + (year - 1988);
		}
	},
    html_encode: function(item)
    {
        return item.replace(/[\x26\x0A\<>'"]/g,function(r){return"&#"+r.charCodeAt(0)+";"})
    }
};