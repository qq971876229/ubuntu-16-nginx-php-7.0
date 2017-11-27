<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  
  <meta name="viewport"
        content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta content="telephone=no" name="format-detection">
  <title>趣聊</title>
  <link rel="stylesheet" href="css/share_app_download/main.css">
  <script src="css/share_app_download/baidustat.js"></script>
  
  
  <style type="text/css">
    * {
      margin: 0;
      padding: 0;
    }
    
    p img {
      max-width: 100%;
      height: auto;
    }
    
    .test {
      height: 600px;
      max-width: 600px;
      font-size: 40px;
    }
  </style>
  <style type="text/css">
    :root .footer > #box[style="width:100%;height:100%;position:fixed;top:0"] {
      display: none !important;
    }</style>
</head>

<body>

<script type="text/javascript">

    function is_weixin() {
        var ua = navigator.userAgent.toLowerCase();

        if (ua.match(/MicroMessenger/i) == "micromessenger") {
            return true;
        } else {
            if (ua.toLowerCase().indexOf("qq") != -1 && ua.toLowerCase().indexOf("mqqbrowser") == -1 && ua.toLowerCase().indexOf("qqbrowser") == -1) {
                return true;
            }
            return false;
        }
    }

    function loadHtml() {
        var div = document.createElement('div');
        var src = $("#tips").val();
        div.id = 'weixin-tip';
        div.innerHTML = '<p><img src="css/share_app_download/WechatIMG76.png" alt="微信打开"/></p>';
        document.body.appendChild(div);
    }

    function loadStyleText(cssText) {
        var style = document.createElement('style');
        style.rel = 'stylesheet';
        style.type = 'text/css';
        try {
            style.appendChild(document.createTextNode(cssText));
        } catch (e) {
            style.styleSheet.cssText = cssText; //ie9以下
        }
        var head = document.getElementsByTagName("head")[0]; //head标签之间加上style样式
        head.appendChild(style);
    }

    function start_download(a_url) {
        var isWeixin = is_weixin();
        if (isWeixin) {
            var cssText = "#weixin-tip{position: fixed; left:0; top:0; background: rgba(0,0,0,0.8); filter:alpha(opacity=80); width: 100%; height:100%; z-index: 100;} #weixin-tip p{text-align: center; margin-top: 10%; padding:0 5%;}";
            var winHeight = typeof window.innerHeight != 'undefined' ? window.innerHeight : document.documentElement.clientHeight;
            loadHtml();
            loadStyleText(cssText);
        } else {
            window.open(a_url);
        }
    }

    if (is_weixin()) {
        var cssText = "#weixin-tip{position: fixed; left:0; top:0; background: rgba(0,0,0,0.8); filter:alpha(opacity=80); width: 100%; height:100%; z-index: 100;} #weixin-tip p{text-align: center; margin-top: 10%; padding:0 5%;}";
        var winHeight = typeof window.innerHeight != 'undefined' ? window.innerHeight : document.documentElement.clientHeight;
        console.log(winHeight);
        loadHtml();
        loadStyleText(cssText);
    }

</script>
<div class="banner" style="overflow: hidden; visibility: visible; list-style: none; position: relative;">
  
  <ul id="slider" style="position: relative; overflow: hidden; transition: left 0ms ease; width: 480px; left: 0px;">
    
    <li style="float: left; display: block; width: 480px;">
      <img src="css/share_app_download/bg_01.gif" width="100%">
      {% if mobile_type=='ios' %}
        
        <a href="{{ url }}" id="qlApp">
          <img src="css/share_app_download/click_me_03.png" alt="苹果" class="banner_down"
               style="position:relative;left: 50%;top:-80px;margin-left: -192px;width: 384px;height: 50px;">
        </a>
      {% else %}
        
        <img src="css/share_app_download/click_me_03.png" alt="安卓" class="banner_down"
             onclick="start_download('{{ url }}')"
             style="position:relative;left: 50%;top:-80px;margin-left: -192px;width: 384px;height: 50px;">
      {% endif %}
  
  
  </ul>
  <div id="pagenavi" style="display:none">
    <a href="http://wap.chumao.org.cn/wap/index.act#" class="active">1</a>
    <a href="http://wap.chumao.org.cn/wap/index.act#">2</a></div>
</div>

<script type="text/javascript">


//    document.getElementById('qlApp').onclick = function (e) {
//        // 通过iframe的方式试图打开APP，如果能正常打开，会直接切换到APP，并自动阻止a标签的默认行为
//        // 否则打开a标签的href链接
//        var ifr = document.createElement('iframe');
//        ifr.src = 'tms-services://';
//        ifr.style.display = 'none';
//        document.body.appendChild(ifr);
//        window.setTimeout(function () {
//            document.body.removeChild(ifr);
//        }, 3000)
//    };
//
//    document.getElementById('openApp').onclick = function (e) {
//        // 通过iframe的方式试图打开APP，如果能正常打开，会直接切换到APP，并自动阻止a标签的默认行为
//        // 否则打开a标签的href链接
//        var ifr = document.createElement('iframe');
//        ifr.src = 'com.baidu.tieba://';
//        ifr.style.display = 'none';
//        document.body.appendChild(ifr);
//        window.setTimeout(function () {
//            document.body.removeChild(ifr);
//        }, 3000)
//    };
</script>

<script>
    //轮播
    eval(function (B, D, A, G, E, F) {
        function C(A) {
            return A < 62 ? String.fromCharCode(A += A < 26 ? 65 : A < 52 ? 71 : -4) : A < 63 ? '_' : A < 64 ? '$' : C(A >> 6) + C(A & 63)
        }

        while (A > 0)E[C(G--)] = D[--A];
        return B.replace(/[\w\$]+/g, function (A) {
            return E[A] == F[A] ? A : E[A]
        })
    }('(1(K,N){"use strict";b G=("createTouch"X 3)||("ontouchstart"X K),I=3.createElement("div").n,E=(1(){b V={OTransform:["-Cy-","otransitionend"],WebkitTransform:["-webkit-","webkitTransitionEnd"],MozTransform:["-moz-","BH"],msTransform:["-BX-","MSTransitionEnd"],transform:["","BH"]},U;e(U X V)W(U X I)s V[U];s l})(),J=[["Bh","k","BK"],["height","top","bottom"]],O=E&&E[R],B=1(V){s(V+"").CD(/^-BX-/,"BX-").CD(/-([CA-C0]|[R-C3])/ig,1(U,V){s(V+"").toUpperCase()})},C=1(V){b U=B(O+V);s(V X I)&&V||(U X I)&&U},H=1(U,V){e(b A X V)W(v U[A]=="6")U[A]=V[A];s U},F=1(V){b A=V.children||V.childNodes,U=[],B=R;e(;B<A.t;B++)W(A[B].BO===S)U.push(A[B]);s U},P=1(V,U){b B=R,A=V.t;e(;B<A;B++)W(U.Bs(V[B],B,V[B])===l)BT},V=1(V){V=U.Z.BB(V);V.$()},M=G?"touchstart":"mousedown",D=G?"touchmove":"mousemove",L=G?"touchend":"mouseup",A=E[S]||"",U=1(V,A){W(!(f instanceof U))s BA U(V,A);W(v V!="CG"&&!V.BO){A=V;V=A.Ct}W(!V.BO)V=3.getElementById(V);f.a=H(A||{},f.Cu);f.y=V;W(f.y){f.8=f.y.CU||3.CJ;f.Bw()}};U.Z=U.prototype={Cl:"S.T.C2",Cu:{Ct:"slider",r:R,BV:j,BE:600,0:5000,5:"k",CQ:"center",Cs:j,Bj:l,B_:BA CB,CN:BA CB},d:1(V,D){W(v D=="CG"){b U=3.CZ&&3.CZ.CW&&CW(V,Bp)||V.currentStyle||V.n||{};s U[B(D)]}h{b A,C;e(A X D){W(A=="B1")C=("Ca"X I)?"Ca":"styleFloat";h C=B(A);V.n[C]=D[A]}}},9:1(U,A,B,V){W(U.Bd){U.Bd(A,B,V);s j}h W(U.Bn){U.Bn("Cd"+A,B);s j}s l},Cw:1(U,A,B,V){W(U.Bd){U.removeEventListener(A,B,V);s j}h W(U.Bn){U.detachEvent("Cd"+A,B);s j}s l},BB:1(B){b U={},C="changedTouches BS Bt w view which B5 B4 fromElement offsetX offsetY o q toElement".split(" ");B=B||K.event;P(C,1(){U[f]=B[f]});U.w=B.w||B.srcElement||3;W(U.w.BO===C1)U.w=U.w.CU;U.$=1(){B.$&&B.$();U.CL=B.CL=l};U.Bu=1(){B.Bu&&B.Bu();U.B7=B.B7=j};W(G&&U.BS.t){U.o=U.BS.Co(R).o;U.q=U.BS.Co(R).q}h W(v B.o=="6"){b A=3.documentElement,V=3.CJ;U.o=B.B5+(A&&A.CH||V&&V.CH||R)-(A&&A.CF||V&&V.CF||R);U.q=B.B4+(A&&A.Cv||V&&V.Cv||R)-(A&&A.B2||V&&V.B2||R)}U.B$=B;s U},i:1(U,V){s 1(){s U.apply(V,arguments)}},Bw:1(){f.u=F(f.y);f.t=f.u.t;f.a.0=BR(f.a.0);f.a.BE=BR(f.a.BE);f.a.r=BR(f.a.r);f.a.BV=!!f.a.BV;f.a.0=g.BP(f.a.0,f.a.BE);f.CX=!!G;f.css3transition=!!E;f.m=f.a.r<R||f.a.r>=f.t?R:f.a.r;W(f.t<S)s l;f.Cf=3.createComment("\\Q Powered by CY Cz"+f.Cl+",\\Q author: Bc,\\Q email: imqiqiboy@gmail.Be,\\Q blog: Ce://www.Bc.Be,\\Q Ch: Ce://Ch.Be/Bc\\Q");f.8.BY(f.Cf,f.y);Ck(f.a.5){BC"CC":BC"down":f.5=f.a.5;f.2=S;BT;BC"BK":f.5="BK";Cr:f.5=f.5||"k";f.2=R;BT}f.9(f.y,M,f.i(f.CR,f),l);f.9(3,D,f.i(f.Cn,f),l);f.9(3,L,f.i(f.Bb,f),l);f.9(3,"touchcancel",f.i(f.Bb,f),l);f.9(f.y,A,f.i(f.BH,f),l);f.9(K,"BI",f.i(1(){_(f.CT);f.CT=BQ(f.i(f.BI,f),Cm)},f),l);W(f.a.Bj){f.9(f.y,"mousewheel",f.i(f.Bv,f),l);f.9(f.y,"DOMMouseScroll",f.i(f.Bv,f),l)}f.z=f.a.BV;f.BI()},x:1(C,U,D){b A=R,E=U,V=B("-"+C);e(;E<D;E++)A+=f["Br"+V](f.u[E]);s A},BZ:1(D,A){b U=B("-"+D),V=f.x(D,A,A+S),C=f.x(D,R,A)+f["Br"+U](f.y)/T-f["Bg"+U](f.y)/T;Ck(f.a.CQ){BC"k":s-C;BC"BK":s f[D]-V-C;Cr:s(f[D]-V)/T-C}},BI:1(){_(f.BW);b A=f,D,C=J[f.2][R],V=B("-"+C),U=f.d(f.8,"Bo");f.d(f.8,{CS:"By",B0:"By",listStyle:"Cp",Bo:U=="static"?"Cq":U});f[C]=f["Bg"+V](f.8);D={B1:f.2?"Cp":"k",display:"block"};P(f.u,1(){W(A.a.Cs)D[C]=A[C]-A["Bm"+V](f)-A["Bf"+V](f)-A["BF"+V](f)+"Y";A.d(f,D)});f.Bz=f.x(C,R,f.t);D={Bo:"Cq",CS:"By"};D[O+"Bx-Cx"]="B8";D[C]=f.Bz+"Y";D[J[f.2][S]]=f.t?f.BZ(C,f.m)+"Y":R;f.d(f.y,D);f.d(f.8,{B0:"visible"});f.z&&f.Ba();s f},BN:1(V,A){b B=J[f.2][S],G=J[f.2][R],E=C("Bx"),L=BD(f.d(f.y,B))||R,N,O={},D,H=f.x(G,V,V+S);V=g.Bq(g.BP(R,V),f.t-S);A=v A=="6"?f.a.BE:BR(A);N=f.BZ(G,V);D=N-L,A=g.c(D)<H?g.B9(g.c(D)/H*A):A;W(E){O[E]=B+" ease "+A+"BX";O[B]=N+"Y";f.d(f.y,O)}h{b M=f,I=R,K=A/CE,U=1(U,A,B,V){s-B*((U=U/V-S)*U*U*U-S)+A},F=1(){W(I<K){I++;M.y.n[B]=g.B9(U(I,L,D,K))+"Y";M.BW=BQ(F,CE)}h{M.y.n[B]=N+"Y";M.BH({CO:B})}};_(f.BW);F()}f.a.B_.Bs(f,V,f.u[f.m]);f.m=V;s f},Ba:1(){_(f.p);f.z=j;f.p=BQ(f.i(1(){f.5=="k"||f.5=="CC"?f.BM():f.BL()},f),f.a.0);s f},CM:1(){_(f.p);f.z=l;s f},stop:1(){f.CM();s f.BN(R)},BL:1(A,U){_(f.p);b V=f.m;A=v A=="6"?A=S:A%f.t;V-=A;W(U===l)V=g.BP(V,R);h V=V<R?f.t+V:V;s f.BN(V)},BM:1(A,U){_(f.p);b V=f.m;W(v A=="6")A=S;V+=A;W(U===l)V=g.Bq(V,f.t-S);h V%=f.t;s f.BN(V)},CR:1(A){A=f.BB(A);b U=A.w.nodeName.B6();W(!f.CX&&(U=="CA"||U=="img"))A.$();f.Cw(f.y,"Cj",V);f.4=[A.o,A.q];f.y.n[B(O+"Bx-Cx")]="B8";f.Bl=+BA Cb;f.Bi=BD(f.d(f.y,J[f.2][S]))||R},Cn:1(A){W(!f.4||A.Bt&&A.Bt!==S)s;A=f.BB(A);f.BJ=[A.o,A.q];b V,U=J[f.2][S],C=J[f.2][R],B=f.BJ[f.2]-f.4[f.2];W(f.7||v f.7=="6"&&g.c(B)>=g.c(f.BJ[S-f.2]-f.4[S-f.2])){A.$();B=B/((!f.m&&B>R||f.m==f.t-S&&B<R)?(g.c(B)/f[C]+S):S);f.y.n[U]=f.Bi+B+"Y";W(K.CI!=Bp){V=CI();W(V.Cc)V.Cc();h W(V.Cg)V.Cg()}W(B&&v f.7=="6"){f.7=j;_(f.p);_(f.BW)}}h f.7=l},Bb:1(E){W(f.4){W(f.7){b K=J[f.2][R],C=J[f.2][S],I=f.BJ[f.2]-f.4[f.2],H=g.c(I),A=H/I,U,G,B,D=f.m,F=R;f.9(f.y,"Cj",V);W(H>20){G=BD(f.d(f.y,J[f.2][S]));do{W(D>=R&&D<f.t){B=f.BZ(K,D);U=f.x(K,D,D+S)}h{D+=A;BT}}while(g.c(B-G)>U/T&&(D-=A))F=g.c(D-f.m);W(!F&&+BA Cb-f.Bl<250)F=S}I>R?f.BL(F,l):f.BM(F,l);f.z&&f.Ba()}BG f.Bi;BG f.BJ;BG f.4;BG f.7;BG f.Bl}},Bv:1(C){W(f.a.Bj){C=f.BB(C);b D=f,B=C.B$,U=R,A=R,V;W("Bk"X B){U=B.Bk;A=B.wheelDeltaY}h W("B3"X B)A=B.B3;h W("CK"X B)A=-B.CK;h s;W(!f.2&&g.c(U)>g.c(A))V=U;h W(A&&(!B.Bk||f.2&&g.c(U)<g.c(A)))V=A;W(V){C.$();_(f.Ci);f.Ci=BQ(1(){V>R?D.BL(S,l):D.BM(S,l)},Cm)}}},BH:1(V){W(V.CO==J[f.2][S]){f.a.CN.Bs(f,f.m,f.u[f.m]);f.z&&f.Ba()}},BU:1(){W(f.5==Bp)f.Bw();h{f.u=F(f.y);f.t=f.u.t;f.m=g.BP(g.Bq(f.t-S,f.m),R);f.BI()}},CP:1(V){f.y.appendChild(V);f.BU()},prepend:1(V){f.t?f.BY(V,R):f.CP(V)},BY:1(V,U){f.y.BY(V,f.u[U]);W(f.m>=U)f.m++;f.BU()},remove:1(V){f.y.removeChild(f.u[V]);W(f.m>=V)f.m--;f.BU()}};P(["Width","Height"],1(B,A){b V=A.B6();P(["Bm","Bf","BF"],1(C,V){U.Z[V+A]=1(U){s(BD(f.d(U,V+"-"+J[B][S]+(V=="BF"?"-Bh":"")))||R)+(BD(f.d(U,V+"-"+J[B][T]+(V=="BF"?"-Bh":"")))||R)}});U.Z["Bg"+A]=1(V){s V["CV"+A]-f["Bf"+A](V)-f["BF"+A](V)};U.Z["Br"+A]=1(V){s V["CV"+A]+f["Bm"+A](V)}});K.CY=U})(window)', 'n|0|1|2|_|$|if|in|px|fn|cfg|var|abs|css|for|this|Math|else|bind|true|left|false|index|style|pageX|timer|pageY|begin|return|length|slides|typeof|target|getSum|element|playing|timeout|function|vertical|document|startPos|direction|undefined|scrolling|container|addListener|clearTimeout|preventDefault|new|eventHook|case|parseFloat|speed|border|delete|transitionend|resize|stopPos|right|prev|next|slide|nodeType|max|setTimeout|parseInt|touches|break|refresh|auto|aniTimer|ms|insertBefore|getPos|play|_end|qiqiboy|addEventListener|com|padding|get|width|_pos|mouseWheel|wheelDeltaX|startTime|margin|attachEvent|position|null|min|getOuter|call|scale|stopPropagation|mouseScroll|setup|transition|hidden|total|visibility|float|clientTop|wheelDelta|clientY|clientX|toLowerCase|cancelBubble|0ms|ceil|before|origEvent|a|Function|up|replace|10|clientLeft|string|scrollLeft|getSelection|body|detail|returnValue|pause|after|propertyName|append|align|_start|overflow|resizeTimer|parentNode|offset|getComputedStyle|touching|TouchSlider|defaultView|cssFloat|Date|empty|on|http|comment|removeAllRanges|weibo|mouseTimer|click|switch|version|100|_move|item|none|relative|default|fixWidth|id|_default|scrollTop|removeListener|duration|o|v|z|3|8|9'.split('|'), 168, 183, {}, {}))
    var t4 = new TouchSlider({id: 'slider', speed: 1000, timeout: 3000});
</script>
<script type="text/javascript" src="css/share_app_download/zepto.js"></script>
<script type="text/javascript">
    var phoneInput = document.getElementById("phone");
    var phoneTips = document.getElementById("phoneTips");
    var smsCodeInput = document.getElementById("smsCode");
    var smsCodeTips = document.getElementById("smsCodeTips");
    function checkPhone() {
        if (!isMobile(phoneInput.value)) {
            phoneTips.innerHTML = "请填写正确的手机号码！";
            phoneTips.style.display = "block";
            return false;
        } else {
            phoneTips.style.display = "none";
            return true;
        }
    }
    phoneInput.onblur = checkPhone;
    phoneInput.onkeyup = function () {
        if (phoneTips.style.display == "block") {
            checkPhone();
        }
    };

    function checkSmsCode() {
        if (!checkByteLength(smsCodeInput.value, 4, 4) || isNaN(smsCodeInput.value)) {
            smsCodeTips.innerHTML = "请填写正确的短信验证码！";
            smsCodeTips.style.display = "block";
            return false;
        } else {
            smsCodeTips.style.display = "none";
            return true;
        }
    }

    function sendSmsSecurityCode() {
        if (checkPhone()) {
            $("#sendButton").hide();
            $.ajax({
                type: 'POST',
                url: '/wap/regMobile.act',
                data: {mobile: phoneInput.value, sign: $("#sign").val()},
                dataType: 'json',
                success: function (data) {
                    if (data.flag) {
                        phoneTips.innerHTML = "验证码发送成功，请注意查收短信";
                        phoneTips.style.display = "block";
                        $("#sendButton").hide();
                        phoneTips.style.color = "green";
                        $("#step2Sign").val(data.msg);
                    } else if (data.code == 7 || data.code == -2 || data.code == 26) {
                        phoneTips.innerHTML = "发送短信次数超限，请明天再试！";
                        $("#sendButton").hide();
                        phoneTips.style.display = "block";
                        phoneTips.style.color = "red";
                    } else if (data.code == 40001) {
                        phoneTips.innerHTML = "判定恶意注册，如有疑问请联系客服！";
                        $("#sendButton").hide();
                        phoneTips.style.display = "block";
                        phoneTips.style.color = "red";
                    } else {
                        phoneTips.innerHTML = "发送短信验证码失败，请联系客服";
                        $("#sendButton").hide();
                        phoneTips.style.display = "block";
                        phoneTips.style.color = "red";
                    }
                }
            });
        }
    }

    function checkSubmit() {
        if (!isMobile(phoneInput.value)) {
            phoneTips.innerHTML = "请填写正确的手机号码！";
            phoneTips.style.display = "block";
            phoneInput.focus();
            return false;
        }
        if (!checkByteLength(smsCodeInput.value, 4, 4) || isNaN(smsCodeInput.value)) {
            smsCodeTips.innerHTML = "请填写正确的短信验证码！";
            smsCodeTips.style.display = "block";
            smsCodeInput.focus();
            return false;
        }
        return true;
    }

    function submitReg() {
        if (checkSubmit()) {
            $.ajax({
                type: 'POST',
                url: '/wap/mobileRegSubmitNopwd.act',
                data: {
                    invitedby: "35139",
                    invitedflag: "1",
                    mobile: phoneInput.value,
                    smsCode: smsCodeInput.value,
                    sign: $("#step2Sign").val()
                },
                dataType: 'json',
                success: function (data) {
                    if (data.flag) {
                        window.location.href = "/wap/toCall.act";
                    } else if (data.code == 1) {
                        smsCodeTips.innerHTML = "短信验证码错误!";
                        smsCodeTips.style.display = "block";
                    } else {
                        smsCodeTips.innerHTML = "注册失败，请联系客服";
                        smsCodeTips.style.display = "block";
                    }
                }
            });
        }
    }

    function isMobile(value) {
        if (/^182\d{8}$/g.test(value) || (/^147\d{8}$/g.test(value))
            || /^13\d{9}$/g.test(value) || (/^15[0-35-9]\d{8}$/g.test(value))
            || (/^18[01-9]\d{8}$/g.test(value))) {
            return true;
        } else {
            return false;
        }
    }
    function checkByteLength(str, minlen, maxlen) {
        if (str == null)
            return false;
        var l = str.length;
        var blen = 0;
        for (var i = 0; i < l; i++) {
            if ((str.charCodeAt(i) & 0xff00) != 0) {
                blen++;
            }
            blen++;
        }
        if (blen > maxlen || blen < minlen) {
            return false;
        }
        return true;
    }
</script>


</body>
</html>