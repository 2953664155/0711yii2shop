<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>用户注册</title>
    <link rel="stylesheet" href="/style/base.css" type="text/css">
    <link rel="stylesheet" href="/style/global.css" type="text/css">
    <link rel="stylesheet" href="/style/header.css" type="text/css">
    <link rel="stylesheet" href="/style/login.css" type="text/css">
    <link rel="stylesheet" href="/style/footer.css" type="text/css">
</head>
<body>
<!-- 顶部导航 start -->
<?php
require "/shop/nav.php";
?>
<!-- 顶部导航 end -->

<div style="clear:both;"></div>

<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="#"><img src="/images/logo.png" alt="京西商城"></a></h2>
    </div>
</div>
<!-- 页面头部 end -->

<!-- 登录主体部分start -->
<div class="login w990 bc mt10 regist">
    <div class="login_hd">
        <h2>用户注册</h2>
        <b></b>
    </div>
    <div class="login_bd">
        <div class="login_form fl">
            <form action="" method="post" id="regist_form">
                <ul>
                    <li>
                        <label for="">用户名：</label>
                        <input name="_csrf" type="hidden" id="_csrf" value="<?= \Yii::$app->request->csrfToken ?>">
                        <input type="text" class="txt" name="username" />
                        <p>3-20位字符，可由中文、字母、数字和下划线组成</p>
                    </li>
                    <li>
                        <label for="">密码：</label>
                        <input type="password" class="txt" name="password_hash" />
                        <p>6-20位字符，可使用字母、数字和符号的组合，不建议使用纯数字、纯字母、纯符号</p>
                    </li>
                    <li>
                        <label for="">邮箱：</label>
                        <input type="text" class="txt" name="email" />
                        <p>邮箱必须合法</p>
                    </li>
                    <li>
                        <label for="">手机号码：</label>
                        <input type="text" class="txt" value="" name="tel" id="tel" placeholder=""/>
                        <p>手机号码必须合法</p>
                    </li>
                    <li>
                        <label for="">验证码：</label>
                        <input type="text" class="txt" value="" placeholder="请输入短信验证码" name="captcha" disabled="disabled" id="captcha"/> <input type="button" onclick="bindPhoneNum(this)" id="get_captcha" value="获取验证码" style="height: 25px;padding:3px 8px"/>

                    </li>
                    <li>
                        <label for="">状态：</label>
                        <input type="radio" name="status" value="1" checked> 正常
                        <input type="radio" name="status" value="0"> 删除
                    </li>
                    <li>
                        <label for="">&nbsp;</label>
                        <input type="checkbox" class="chb" checked="checked" /> 我已阅读并同意《用户注册协议》
                    </li>
                    <li>
                        <label for="">&nbsp;</label>
                        <input type="submit" value="" class="login_btn" />
                    </li>
                </ul>
            </form>


        </div>

        <div class="mobile fl">
            <h3>手机快速注册</h3>
            <p>中国大陆手机用户，编辑短信 “<strong>XX</strong>”发送到：</p>
            <p><strong>1069099988</strong></p>
        </div>

    </div>
</div>
<!-- 登录主体部分end -->

<div style="clear:both;"></div>
<!-- 底部版权 start -->
<div class="footer w1210 bc mt15">
    <p class="links">
        <a href="">关于我们</a> |
        <a href="">联系我们</a> |
        <a href="">人才招聘</a> |
        <a href="">商家入驻</a> |
        <a href="">千寻网</a> |
        <a href="">奢侈品网</a> |
        <a href="">广告服务</a> |
        <a href="">移动终端</a> |
        <a href="">友情链接</a> |
        <a href="">销售联盟</a> |
        <a href="">京西论坛</a>
    </p>
    <p class="copyright">
        © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号
    </p>
    <p class="auth">
        <a href=""><img src="/images/xin.png" alt="" /></a>
        <a href=""><img src="/images/kexin.jpg" alt="" /></a>
        <a href=""><img src="/images/police.jpg" alt="" /></a>
        <a href=""><img src="/images/beian.gif" alt="" /></a>
        <script src="http://static.runoob.com/assets/jquery-validation-1.14.0/lib/jquery.js"></script>
        <script src="http://static.runoob.com/assets/jquery-validation-1.14.0/dist/jquery.validate.min.js"></script>
    </p>
</div>
<!-- 底部版权 end -->
<script>
    $().ready(function() {
// 在键盘按下并释放及提交后验证提交表单
        $("#regist_form").validate({
            rules: {
                username: {
                    required: true,
                    minlength: 2,
                    remote: {
                        url:"<?=\yii\helpers\Url::to(['member/check-name'])?>"
                    }
                },
                password_hash: {
                    required: true,
                    minlength: 6
                },
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url:"<?=\yii\helpers\Url::to(['member/check-email'])?>"
                    }
                },
                captcha: {
                    required: true,
                    remote:{
                        url: "check-sms",
                        type: "get",
                        dataType: "json",
                        data:{
                            tel: function () {
                                return $("#tel").val();
                            },
                            captcha: function () {
                                return $("#captcha").val();
                            }
                        }
                    }
                },
                tel: {
                    required: true,
                    minlength: 11,
                    digits:true,
                    remote: {
                        url:"<?=\yii\helpers\Url::to(['member/check-tel'])?>"
                    }
                }
            },
            messages: {
                username: {
                    required: "请输入用户名",
                    minlength: "长度必须为2-20位",
                    remote: "用户已经存在"
                },
                password_hash: {
                    required: "请输入密码",
                    minlength: "长度必须为6-20位"
                },
                email: {
                    required: "请输入邮箱",
                    email: "请输入一个正确的邮箱",
                    remote: "邮箱已经存在"
                },
                tel: {
                    required: "请输入电话号码",
                    digits:"必须为整数",
                    minlength: "长度必须为11位",
                    remote: "电话已经存在"
                },
                captcha: {
                    required: "请输入验证码",
                    remote: "验证码错误"
                }
            },
            errorElement : 'span'
        })
});
</script>
<script type="text/javascript">
    function bindPhoneNum(){
        //获取电话号码
        var  phone = $("#tel").val();
        if(phone.length !== 11){
            alert('电话号码必须为11位')
        }
        $.get("<?=\yii\helpers\Url::to(['member/ajax-sms'])?>",{phone:phone},function (data) {
            if(data == 1){
                alert('短信发送成功')
            }else {
                alert('短信发送失败!!!');
            }
        });
        //启用输入框
        $('#captcha').prop('disabled',false);
        var time=60;
        var interval = setInterval(function(){
            time--;
            if(time<=0){
                clearInterval(interval);
                var html = '获取验证码';
                $('#get_captcha').prop('disabled',false);
            } else{
                var html = time + ' 秒后再次获取';
                $('#get_captcha').prop('disabled',true);
            }

            $('#get_captcha').val(html);
        },1000);
    }
</script>
</body>
</html>