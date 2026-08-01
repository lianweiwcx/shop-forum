<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
// 页面顶部布局：依赖 core/functions.php 已加载
$user = currentUser();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(siteSetting('site_name') ?: 'AI 商城社区'); ?><?php echo isset($pageTitle) ? ' - ' . e($pageTitle) : ''; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="topbar">
    <div class="container">
        <a class="brand" href="index.php"><?php echo e(siteSetting('site_name') ?: 'AI 商城社区'); ?></a>
        <div class="nav">
            <a href="index.php">首页</a>
            <a href="product.php">AI 商城</a>
            <a href="community.php">AI 社区</a>
            <a href="cart.php">购物车<?php $cc = cartCount(); if ($cc > 0): ?><span class="badge"><?php echo $cc; ?></span><?php endif; ?></a>
        </div>
        <div class="top-actions">
            <?php if ($user): ?>
                <?php if ($user['role'] >= 1): ?><a class="btn-ghost" href="merchant.php?action=myshop">我的店铺</a><?php endif; ?>
                <?php if ($user['role'] == 2): ?><a class="btn-ghost" href="admin/">管理后台</a><?php endif; ?>
                <a class="btn-ghost" href="auth.php?action=profile">你好，<?php echo e($user['nickname'] ?: $user['username']); ?></a>
                <a class="btn-ghost" href="order.php">我的订单</a>
                <a class="btn-ghost" href="auth.php?action=logout">退出</a>
            <?php else: ?>
                <a class="btn-ghost" href="auth.php?action=login">登录</a>
                <a class="btn-ghost" href="auth.php?action=register">注册</a>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="container">
<?php $msg = flash(); if ($msg): ?>
    <div class="flash"><?php echo e($msg); ?></div>
<?php endif; ?>
