<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Merchant.php';
require_once __DIR__ . '/../models/Category.php';

$pageTitle = '商品管理';
$productModel  = new Product();
$merchantModel = new Merchant();
$categoryModel = new Category();

// 新增 / 编辑保存
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $id        = (int)($_POST['id'] ?? 0);
    $image     = handleUpload('image', 'products/');
    $merchantId = (int)$_POST['merchant_id'];
    $category  = trim($_POST['category']);
    $title     = trim($_POST['title']);
    $price     = (float)$_POST['price'];
    $stock     = (int)$_POST['stock'];
    $desc      = trim($_POST['description']);
    if ($id) {
        $productModel->update($id, $title, $price, $stock, $category, $image, $desc);
        flash('商品已更新');
    } else {
        $productModel->create($merchantId, $title, $price, $stock, $category, $image, $desc);
        flash('商品已新增');
    }
    redirect('products.php');
}

// 上/下架 / 删除
if (isset($_GET['op'], $_GET['id'])) {
    $id = (int)$_GET['id'];
    $p = $productModel->getById($id);
    if ($p) {
        if ($_GET['op'] === 'toggle') {
            $productModel->setStatus($id, $p['status'] ? 0 : 1);
            flash('商品状态已更新');
        } elseif ($_GET['op'] === 'delete') {
            $productModel->delete($id);
            flash('商品已删除');
        }
    }
    redirect('products.php');
}

$editProduct = isset($_GET['edit']) ? $productModel->getById((int)$_GET['edit']) : null;
$merchants   = $merchantModel->listApproved();
$categories  = $categoryModel->listAll();
$products    = $productModel->listAll();

require 'layout/header.php';
?>
<h2>商品管理</h2>

<div class="card" style="max-width:560px;">
    <strong><?php echo $editProduct ? '编辑商品 #' . $editProduct['id'] : '新增商品'; ?></strong>
    <form method="post" enctype="multipart/form-data" style="margin-top:8px;">
        <?php if ($editProduct): ?><input type="hidden" name="id" value="<?php echo $editProduct['id']; ?>"><?php endif; ?>
        <label>商品标题</label>
        <input type="text" name="title" value="<?php echo e($editProduct['title'] ?? ''); ?>" required>
        <label>价格（元）</label>
        <input type="number" step="0.01" name="price" value="<?php echo e($editProduct['price'] ?? ''); ?>" required>
        <label>库存</label>
        <input type="number" name="stock" value="<?php echo e($editProduct['stock'] ?? 0); ?>" required>
        <label>所属商家</label>
        <select name="merchant_id" required>
            <?php foreach ($merchants as $m): ?>
                <option value="<?php echo $m['id']; ?>"
                    <?php echo $editProduct && $editProduct['merchant_id'] == $m['id'] ? 'selected' : ''; ?>>
                    <?php echo e($m['shop_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label>分类</label>
        <select name="category" required>
            <option value="">请选择分类</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?php echo e($c['name']); ?>"
                    <?php echo $editProduct && $editProduct['category'] == $c['name'] ? 'selected' : ''; ?>>
                    <?php echo e($c['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label>主图</label>
        <input type="file" name="image" accept="image/*">
        <label>商品描述</label>
        <textarea name="description"><?php echo e($editProduct['description'] ?? ''); ?></textarea>
        <div class="row" style="margin-top:12px;">
            <button class="btn" type="submit" name="save">保存</button>
            <?php if ($editProduct): ?><a class="btn btn-secondary" href="products.php">取消</a><?php endif; ?>
        </div>
    </form>
</div>

<table>
    <tr><th>ID</th><th>标题</th><th>价格</th><th>店铺</th><th>分类</th><th>状态</th><th>操作</th></tr>
    <?php foreach ($products as $p): ?>
        <tr>
            <td><?php echo $p['id']; ?></td>
            <td><a href="../product.php?action=detail&id=<?php echo $p['id']; ?>"><?php echo e($p['title']); ?></a></td>
            <td>￥<?php echo e($p['price']); ?></td>
            <td><?php echo e($p['shop_name'] ?: ''); ?></td>
            <td><?php echo e($p['category']); ?></td>
            <td><?php echo $p['status'] ? '上架' : '下架'; ?></td>
            <td>
                <a class="btn btn-secondary" href="products.php?op=toggle&id=<?php echo $p['id']; ?>">
                    <?php echo $p['status'] ? '下架' : '上架'; ?>
                </a>
                <a class="btn btn-secondary" href="products.php?edit=<?php echo $p['id']; ?>">编辑</a>
                <a class="btn btn-danger" href="products.php?op=delete&id=<?php echo $p['id']; ?>"
                   onclick="return confirm('确定删除该商品？');">删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($products)): ?><tr><td colspan="7" class="muted">暂无商品</td></tr><?php endif; ?>
</table>
<?php require 'layout/footer.php'; ?>
