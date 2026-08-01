<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once 'auth_check.php';
require_once __DIR__ . '/../models/Banner.php';

$pageTitle = '轮播图管理';
$bannerModel = new Banner();

// 操作处理
if (isset($_GET['op'])) {
    $id = (int)($_GET['id'] ?? 0);
    if ($_GET['op'] === 'delete') {
        $bannerModel->delete($id);
        flash('轮播图已删除');
    } elseif ($_GET['op'] === 'toggle') {
        $b = $bannerModel->getById($id);
        if ($b) {
            $bannerModel->setStatus($id, $b['status'] ? 0 : 1);
            flash('状态已更新');
        }
    } elseif ($_GET['op'] === 'edit') {
        $edit = $bannerModel->getById($id);
    }
    if ($_GET['op'] === 'delete' || $_GET['op'] === 'toggle') {
        redirect('banners.php');
    }
}

// 保存（新增/编辑）
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title  = trim($_POST['title'] ?? '');
    $link   = trim($_POST['link'] ?? '');
    $sort   = (int)($_POST['sort'] ?? 0);
    $status = isset($_POST['status']) ? 1 : 0;
    $image  = handleUpload('image', 'banners/');

    if (isset($_POST['id']) && $_POST['id']) {
        $bannerModel->update((int)$_POST['id'], $title, $image, $link, $sort, $status);
        flash('轮播图已更新');
    } else {
        if (!$image) {
            $error = '请上传轮播图图片';
        } else {
            $bannerModel->create($title, $image, $link, $sort, $status);
            flash('轮播图已添加');
        }
    }
    redirect('banners.php');
}

$banners = $bannerModel->listAll();

require 'layout/header.php';
?>
<h2>轮播图管理</h2>

<div class="card" style="max-width:640px;">
    <?php if (isset($error)): ?><div class="flash error"><?php echo e($error); ?></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo e($edit['id'] ?? ''); ?>">
        <label>标题（可选）</label>
        <input type="text" name="title" value="<?php echo e($edit['title'] ?? ''); ?>" placeholder="如：夏季大促">
        <label>图片（必填，建议 1200×360）</label>
        <input type="file" name="image" accept="image/*" <?php echo isset($edit) ? '' : 'required'; ?>>
        <?php if (isset($edit) && $edit['image']): ?>
            <p class="muted"><img src="<?php echo e($edit['image']); ?>" style="max-width:160px;border-radius:8px;"></p>
        <?php endif; ?>
        <label>跳转链接（可选）</label>
        <input type="text" name="link" value="<?php echo e($edit['link'] ?? ''); ?>" placeholder="如：product.php 或 https://...">
        <label>排序（数字越小越靠前）</label>
        <input type="number" name="sort" value="<?php echo e($edit['sort'] ?? 0); ?>">
        <label><input type="checkbox" name="status" <?php echo !isset($edit) || $edit['status'] ? 'checked' : ''; ?>>
            显示在前台</label>
        <div style="margin-top:14px;">
            <button class="btn" type="submit"><?php echo isset($edit) ? '保存修改' : '添加轮播图'; ?></button>
            <?php if (isset($edit)): ?><a class="btn btn-secondary" href="banners.php">取消</a><?php endif; ?>
        </div>
    </form>
</div>

<table style="margin-top:18px;">
    <thead>
        <tr><th>图片</th><th>标题</th><th>链接</th><th>排序</th><th>状态</th><th>操作</th></tr>
    </thead>
    <tbody>
        <?php foreach ($banners as $b): ?>
            <tr>
                <td>
                    <?php if ($b['image']): ?>
                        <img src="<?php echo e($b['image']); ?>" style="height:46px;border-radius:6px;" alt="">
                    <?php else: ?><span class="muted">—</span><?php endif; ?>
                </td>
                <td><?php echo e($b['title'] ?: '—'); ?></td>
                <td class="muted"><?php echo e($b['link'] ?: '—'); ?></td>
                <td><?php echo e($b['sort']); ?></td>
                <td>
                    <span class="pill" style="background:<?php echo $b['status'] ? '#e6f4ea;color:#1e7e34' : '#fdecea;color:#c62828'; ?>">
                        <?php echo $b['status'] ? '显示' : '隐藏'; ?>
                    </span>
                </td>
                <td>
                    <a class="btn btn-secondary" href="banners.php?op=edit&id=<?php echo $b['id']; ?>">编辑</a>
                    <a class="btn btn-secondary" href="banners.php?op=toggle&id=<?php echo $b['id']; ?>">
                        <?php echo $b['status'] ? '隐藏' : '显示'; ?>
                    </a>
                    <a class="btn btn-danger" href="banners.php?op=delete&id=<?php echo $b['id']; ?>"
                       onclick="return confirm('确定删除？')">删除</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($banners)): ?><tr><td colspan="6" class="muted">暂无轮播图</td></tr><?php endif; ?>
    </tbody>
</table>
<?php require 'layout/footer.php'; ?>
