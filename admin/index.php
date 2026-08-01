<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Merchant.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Reply.php';

$pageTitle = '概览';
$userModel      = new User();
$merchantModel  = new Merchant();
$productModel   = new Product();
$postModel      = new Post();
$replyModel     = new Reply();

$stats = [
    '用户总数' => $userModel->count(),
    '商家数'   => $userModel->countByRole(1),
    '待审商家' => $merchantModel->countByStatus(0),
    '商品总数' => $productModel->countAll(),
    '帖子总数' => $postModel->countAll(),
    '回复总数' => $replyModel->countAll(),
];

require 'layout/header.php';
?>
<h2>数据概览</h2>
<div class="stat-grid">
    <?php foreach ($stats as $label => $num): ?>
        <div class="stat">
            <div class="num"><?php echo $num; ?></div>
            <div class="muted"><?php echo $label; ?></div>
        </div>
    <?php endforeach; ?>
</div>

<h3 style="margin-top:24px;">快捷操作</h3>
<div class="row">
    <a class="btn" href="merchants.php">处理商家入驻（<?php echo $stats['待审商家']; ?>）</a>
    <a class="btn btn-secondary" href="users.php">管理用户</a>
    <a class="btn btn-secondary" href="products.php">管理商品</a>
</div>
<?php require 'layout/footer.php'; ?>
