<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../models/Merchant.php';
require_once __DIR__ . '/../models/User.php';

$pageTitle = '商家审核';
$merchantModel = new Merchant();
$userModel = new User();

// 审核操作
if (isset($_GET['op'], $_GET['id'])) {
    $id = (int)$_GET['id'];
    $m = $merchantModel->getById($id);
    if ($m) {
        if ($_GET['op'] === 'approve') {
            $merchantModel->audit($id, 1);
            $userModel->setRole($m['user_id'], 1);
            flash('已通过该商家入驻');
        } elseif ($_GET['op'] === 'reject') {
            $merchantModel->audit($id, 2);
            flash('已拒绝该商家入驻');
        }
    }
    redirect('merchants.php');
}

$pending = $merchantModel->listPending();
$all     = $merchantModel->listAll();

require 'layout/header.php';
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
                <a class="btn" href="merchants.php?op=approve&id=<?php echo $m['id']; ?>">通过</a>
                <a class="btn btn-danger" href="merchants.php?op=reject&id=<?php echo $m['id']; ?>">拒绝</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($pending)): ?><tr><td colspan="5" class="muted">暂无待审核申请</td></tr><?php endif; ?>
</table>

<h3 style="margin-top:24px;">全部商家</h3>
<table>
    <tr><th>店铺</th><th>申请人</th><th>联系方式</th><th>状态</th><th>申请时间</th></tr>
    <?php foreach ($all as $m): ?>
        <tr>
            <td><?php echo e($m['shop_name']); ?></td>
            <td><?php echo e($m['nickname'] ?: $m['username']); ?></td>
            <td><?php echo e($m['contact']); ?></td>
            <td>
                <?php echo $m['audit_status'] == 1 ? '已通过' : ($m['audit_status'] == 2 ? '已拒绝' : '待审核'); ?>
            </td>
            <td class="muted"><?php echo e($m['created_at']); ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php require 'layout/footer.php'; ?>
