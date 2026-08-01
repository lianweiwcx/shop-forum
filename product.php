<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once 'core/functions.php';
require_once 'models/Product.php';
require_once 'models/Merchant.php';
require_once 'models/Category.php';

$action = $_GET['action'] ?? 'list';
$productModel = new Product();
$merchantModel = new Merchant();
$error = '';

// 商品列表（带搜索）
if ($action === 'list') {
    $keyword = trim($_GET['kw'] ?? '');
    $category = trim($_GET['cat'] ?? '');
    $products = $productModel->listOnSale($keyword, $category);
    $categories = $productModel->listCategories();
}

// 加入购物车
if ($action === 'addcart') {
    requireLogin();
    $id = (int)($_GET['id'] ?? 0);
    $qty = max(1, (int)($_GET['qty'] ?? 1));
    $p = $productModel->getById($id);
    if ($p && $p['status'] == 1) {
        if ($p['stock'] < $qty) {
            flash('库存不足');
        } else {
            cartAdd($id, $qty);
            flash('已加入购物车');
        }
    }
    redirect('cart.php');
}

// 商品详情
if ($action === 'detail') {
    $product = $productModel->getById((int)($_GET['id'] ?? 0));
    if (!$product) {
        flash('商品不存在');
        redirect('product.php');
    }
}

// 发布商品（仅商家）
if ($action === 'publish') {
    requireRole(1);
    $categories = (new Category())->listAll();
    $merchant = $merchantModel->getByUserId($_SESSION['user_id']);
    if (!$merchant || $merchant['audit_status'] != 1) {
        flash('请先通过商家入驻审核');
        redirect('merchant.php?action=apply');
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $image = handleUpload('image', 'products/');
        $productModel->create(
            $merchant['id'],
            trim($_POST['title']),
            (float)$_POST['price'],
            (int)$_POST['stock'],
            trim($_POST['category']),
            $image,
            trim($_POST['description'])
        );
        flash('商品发布成功');
        redirect('merchant.php?action=myshop');
    }
}

// 编辑商品（仅所属商家）
if ($action === 'edit') {
    requireRole(1);
    $categories = (new Category())->listAll();
    $id = (int)($_GET['id'] ?? 0);
    $product = $productModel->getById($id);
    $merchant = $merchantModel->getByUserId($_SESSION['user_id']);
    if (!$product || !$merchant || $product['merchant_id'] != $merchant['id']) {
        flash('无权操作该商品');
        redirect('merchant.php?action=myshop');
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $image = handleUpload('image', 'products/');
        $productModel->update(
            $id,
            trim($_POST['title']),
            (float)$_POST['price'],
            (int)$_POST['stock'],
            trim($_POST['category']),
            $image,
            trim($_POST['description'])
        );
        flash('商品已更新');
        redirect('merchant.php?action=myshop');
    }
}

// 删除 / 上下架（商家本人或管理员）
if ($action === 'delete' || $action === 'toggle') {
    $id = (int)($_GET['id'] ?? 0);
    $product = $productModel->getById($id);
    $u = currentUser();
    $merchant = $u ? $merchantModel->getByUserId($u['id']) : null;
    $owner = $merchant && $product && $product['merchant_id'] == $merchant['id'];
    if (!$product || (!$owner && $u['role'] != 2)) {
        flash('无权操作');
        redirect('product.php');
    }
    if ($action === 'delete') {
        $productModel->delete($id);
        flash('商品已删除');
    } else {
        $productModel->setStatus($id, $product['status'] ? 0 : 1);
        flash('状态已更新');
    }
    redirect($u['role'] == 2 ? 'admin.php' : 'merchant.php?action=myshop');
}

require 'views/layout/header.php';

if ($action === 'list'):
?>
<h2>AI 商城</h2>
<p class="muted" style="margin-top:-6px;">发现 AI 工具、智能服务与创作者好物</p>
<form method="get" class="card" style="max-width:640px;">
    <div class="row">
        <input type="text" name="kw" placeholder="搜索商品名称" value="<?php echo e($keyword); ?>">
        <select name="cat">
            <option value="">全部分类</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?php echo e($c); ?>" <?php echo $category == $c ? 'selected' : ''; ?>>
                    <?php echo e($c); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button class="btn" type="submit">筛选</button>
    </div>
</form>
<div class="grid">
    <?php foreach ($products as $p): ?>
        <div class="item">
            <span class="ai-badge">AI</span>
            <a href="product.php?action=detail&id=<?php echo $p['id']; ?>">
                <img src="<?php echo e($p['image'] ?: 'assets/css/style.css'); ?>" alt=""
                     onerror="this.style.display='none'">
            </a>
            <div class="body">
                <a href="product.php?action=detail&id=<?php echo $p['id']; ?>"><?php echo e($p['title']); ?></a>
                <div class="price">￥<?php echo e($p['price']); ?></div>
                <div class="muted"><?php echo e($p['shop_name'] ?: '店铺'); ?></div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (empty($products)): ?><p class="muted">没有找到商品</p><?php endif; ?>
</div>
<?php
elseif ($action === 'detail'):
?>
<div class="row" style="gap:10px;align-items:center;margin:26px 0 14px;">
    <span class="ai-badge" style="position:static;">AI</span>
    <h2 style="margin:0;"><?php echo e($product['title']); ?></h2>
</div>
<div class="card row" style="align-items:flex-start;">
    <img src="<?php echo e($product['image'] ?: 'assets/css/style.css'); ?>" alt=""
         style="width:260px;border-radius:8px;" onerror="this.style.display='none'">
    <div>
        <div class="price" style="font-size:22px;">￥<?php echo e($product['price']); ?></div>
        <p><span class="pill">AI 智能服务</span> <span class="pill">分类：<?php echo e($product['category']); ?></span></p>
        <p>库存：<?php echo e($product['stock']); ?></p>
        <p class="muted">店铺：<?php echo e($product['shop_name'] ?: '店铺'); ?></p>
        <p><?php echo nl2br(e($product['description'])); ?></p>
        <?php if ($product['status'] == 1): ?>
        <div class="row" style="margin-top:16px;">
            <input type="number" id="buyQty" value="1" min="1" max="<?php echo e($product['stock']); ?>"
                   style="width:80px;padding:9px 12px;border:1px solid var(--border);border-radius:8px;">
            <a class="btn" href="#" onclick="buy(<?php echo $product['id']; ?>);return false;">加入购物车</a>
            <a class="btn btn-secondary" href="order.php?action=buy&id=<?php echo $product['id']; ?>">立即购买</a>
        </div>
        <?php else: ?>
            <p class="pill" style="background:#fdecea;color:#c62828;">该商品已下架</p>
        <?php endif; ?>
    </div>
</div>
<script>
function buy(id) {
    const qty = document.getElementById('buyQty').value;
    location.href = 'product.php?action=addcart&id=' + id + '&qty=' + qty;
}
</script>
<?php
elseif ($action === 'publish' || $action === 'edit'):
?>
<h2><?php echo $action == 'publish' ? '发布商品' : '编辑商品'; ?></h2>
<div class="card" style="max-width:560px;">
    <?php if ($error): ?><div class="flash error"><?php echo e($error); ?></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <label>商品标题</label>
        <input type="text" name="title" value="<?php echo e($product['title'] ?? ''); ?>" required>
        <label>价格（元）</label>
        <input type="number" step="0.01" name="price" value="<?php echo e($product['price'] ?? ''); ?>" required>
        <label>库存</label>
        <input type="number" name="stock" value="<?php echo e($product['stock'] ?? 0); ?>" required>
        <label>分类</label>
        <select name="category" required>
            <option value="">请选择分类</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?php echo e($c['name']); ?>" <?php echo isset($product) && $product['category'] == $c['name'] ? 'selected' : ''; ?>>
                    <?php echo e($c['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label>主图</label>
        <input type="file" name="image" accept="image/*">
        <label>商品描述</label>
        <textarea name="description"><?php echo e($product['description'] ?? ''); ?></textarea>
        <button class="btn" type="submit">保存</button>
    </form>
</div>
<?php
endif;
require 'views/layout/footer.php';
