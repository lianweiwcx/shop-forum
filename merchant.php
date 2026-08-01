<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once 'core/functions.php';
require_once 'models/Merchant.php';
require_once 'models/Product.php';

$action = $_GET['action'] ?? 'apply';
$merchantModel = new Merchant();
$productModel = new Product();
$error = '';

// 商家入驻申请
if ($action === 'apply') {
    requireLogin();
    $existing = $merchantModel->getByUserId($_SESSION['user_id']);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $merchantModel->apply(
            $_SESSION['user_id'],
            trim($_POST['shop_name']),
            trim($_POST['contact']),
            trim($_POST['description'])
        );
        flash('入驻申请已提交，请等待审核');
        redirect('merchant.php?action=myshop');
    }
}

// 我的店铺（仅商家）
if ($action === 'myshop') {
    requireRole(1);
    $merchant = $merchantModel->getByUserId($_SESSION['user_id']);
    if (!$merchant || $merchant['audit_status'] != 1) {
        flash('您尚未通过商家审核');
        redirect('merchant.php?action=apply');
    }
    $products = $productModel->listByMerchant($merchant['id']);
}

// 管理后台：待审核列表
if ($action === 'audit') {
    requireRole(2);
    $pending = $merchantModel->listPending();
}

// 审核通过 / 拒绝
if ($action === 'approve' || $action === 'reject') {
    requireRole(2);
    $id = (int)($_GET['id'] ?? 0);
    $m = $merchantModel->getById($id);
    if ($m) {
        if ($action === 'approve') {
            $merchantModel->audit($id, 1);
            (new User())->setRole($m['user_id'], 1);
            flash('已通过该商家入驻');
        } else {
            $merchantModel->audit($id, 2);
            flash('已拒绝该商家入驻');
        }
    }
    redirect('merchant.php?action=audit');
}

require 'views/layout/header.php';

if ($action === 'apply'):
    requireLogin();
?>
<h2>申请成为商家</h2>
<div class="card" style="max-width:520px;">
    <?php if ($existing): ?>
        <p class="flash">您已提交申请，当前状态：
            <?php
            echo $existing['audit_status'] == 1 ? '已通过' : ($existing['audit_status'] == 2 ? '已拒绝' : '待审核');
            ?>
        </p>
    <?php else: ?>
        <?php if ($error): ?><div class="flash error"><?php echo e($error); ?></div><?php endif; ?>
        <form method="post">
            <label>店铺名称</label>
            <input type="text" name="shop_name" required>
            <label>联系方式</label>
            <input type="text" name="contact">
            <label>店铺简介</label>
            <textarea name="description"></textarea>
            <button class="btn" type="submit">提交申请</button>
        </form>
    <?php endif; ?>
</div>
<?php
elseif ($action === 'myshop'):
?>
<h2>我的店铺</h2>
<div class="row" style="margin-bottom:12px;">
    <a class="btn" href="product.php?action=publish">发布商品</a>
    <span class="muted">店铺：<?php echo e($merchant['shop_name']); ?></span>
</div>
<div class="grid">
    <?php foreach ($products as $p): ?>
        <div class="item">
            <img src="<?php echo e($p['image'] ?: 'assets/css/style.css'); ?>" alt=""
                 onerror="this.style.display='none'">
            <div class="body">
                <a href="product.php?action=detail&id=<?php echo $p['id']; ?>"><?php echo e($p['title']); ?></a>
                <div class="price">￥<?php echo e($p['price']); ?></div>
                <div class="row">
                    <span class="pill"><?php echo $p['status'] ? '上架中' : '已下架'; ?></span>
                    <a href="product.php?action=edit&id=<?php echo $p['id']; ?>">编辑</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (empty($products)): ?><p class="muted">还没有发布商品</p><?php endif; ?>
</div>
<?php
elseif ($action === 'audit'):
?>
<h2>商家入驻审核</h2>
<table>
    <tr><th>店铺</th><th>申请人</th><th>联系方式</th><th>简介</th><th>操作</th></tr>
    <?php foreach ($pending as $m): ?>
        <tr>
            <td><?php echo e($m['shop_name']); ?></td>
            <td><?php echo e($m['nickname'] ?: $m['username']); ?></td>
            <td><?php echo e($m['contact']); ?></td>
            <td class="muted"><?php echo e(mb_substr($m['description'], 0, 30)); ?></td>
            <td>
                <a class="btn" href="merchant.php?action=approve&id=<?php echo $m['id']; ?>">通过</a>
                <a class="btn btn-danger" href="merchant.php?action=reject&id=<?php echo $m['id']; ?>">拒绝</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($pending)): ?><tr><td colspan="5" class="muted">暂无待审核申请</td></tr><?php endif; ?>
</table>
<?php
endif;
require 'views/layout/footer.php';
