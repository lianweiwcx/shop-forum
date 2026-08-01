<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
$adminNav = [
    'index.php'     => '概览',
    'users.php'     => '用户管理',
    'merchants.php' => '商家审核',
    'products.php'  => '商品管理',
    'banners.php'   => '轮播图',
    'posts.php'     => '帖子管理',
    'replies.php'   => '回复管理',
    'categories.php'=> '分类 / 话题',
    'settings.php'  => '网站设置',
];
$current   = basename($_SERVER['PHP_SELF']);
$adminName = $_SESSION['admin_user'] ?? '管理员';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? e($pageTitle) . ' - 管理后台' : '管理后台'; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { display: flex; margin: 0; }
        .sidebar { width: 200px; min-height: 100vh; background: #1f2a44; color: #fff;
                   display: flex; flex-direction: column; padding: 16px 0; }
        .sidebar .logo { font-weight: 700; font-size: 18px; padding: 0 16px 16px; }
        .sidebar a { display: block; color: #cfd6e4; padding: 10px 16px; font-size: 14px; }
        .sidebar a:hover, .sidebar a.active { background: #2d3a5c; color: #fff; text-decoration: none; }
        .sidebar .bottom { margin-top: auto; padding: 12px 16px; font-size: 13px; color: #9fb0d0; }
        .sidebar .bottom a { padding: 4px 0; color: #9fb0d0; }
        .main { flex: 1; padding: 24px; }
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 16px; }
        .stat { background: #fff; border-radius: 8px; padding: 18px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .stat .num { font-size: 28px; font-weight: 700; color: #2d6cdf; }
    </style>
</head>
<body>
<aside class="sidebar">
    <div class="logo">管理后台</div>
    <?php foreach ($adminNav as $file => $label): ?>
        <a class="<?php echo $current === $file ? 'active' : ''; ?>" href="<?php echo $file; ?>"><?php echo $label; ?></a>
    <?php endforeach; ?>
    <div class="bottom">
        <div>当前：<?php echo e($adminName); ?></div>
        <a href="logout.php">退出登录</a><br>
        <a href="../index.php">返回前台</a>
    </div>
</aside>
<div class="main">
