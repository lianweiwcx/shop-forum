<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
// 后台访问校验：未登录管理员则跳转到登录页
require_once __DIR__ . '/../core/functions.php';
require_once __DIR__ . '/../models/Banner.php';
require_once __DIR__ . '/../models/Order.php';

if (empty($_SESSION['admin_id'])) {
    redirect('login.php');
}
