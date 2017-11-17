<div class="topnav">
    <div class="topnav_bd w1210 bc">
        <div class="topnav_left">

        </div>
        <div class="topnav_right fr">
            <ul>
                <li>您好，欢迎来到京西！
                    <?php
                    if(!Yii::$app->user->isGuest){
                        echo '[<a href="http://www.yii2shop.com/member/logout">退出登录</a>]';
                    }else{
                        echo '[<a href="http://www.yii2shop.com/member/login">登录</a>]';
                        echo "[<a href='http://www.yii2shop.com/member/add'>免费注册</a>]";
                    }
                    ?>

                </li>
                <li class="line">|</li>
                <li>我的订单</li>
                <li class="line">|</li>
                <li>客户服务</li>

            </ul>
        </div>
    </div>
</div>