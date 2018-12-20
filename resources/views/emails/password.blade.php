<?php
/**
 * Created by PhpStorm.
 * User: flytienon
 * Date: 2018/12/20
 * Time: 16:35
 */
?>
<p>点击下面链接重置密码：</p>

<a href="{{ route('password.update') . '/' . $token }}">
    {{ route('password.update') . '/' . $token }}
</a>
