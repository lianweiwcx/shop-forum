<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once 'core/functions.php';
require_once 'models/Product.php';
require_once 'models/Order.php';

$productModel = new Product();
$orderModel = new Order();
$action = $_GET['action'] ?? 'list';

// 立即购买：把单个商品放入 buynow 会话后去结算
if ($action === 'buy') {
    requireLogin();
    $id = (int)($_GET['id'] ?? 0);
    $qty = max(1, (int)($_GET['qty'] ?? 1));
    $p = $productModel->getById($id);
    if (!$p || $p['status'] != 1) {
        flash('商品不可购买');
        redirect('product.php');
    }
    if ($p['stock'] < $qty) {
        flash('库存不足');
        redirect('product.php?action=detail&id=' . $id);
    }
    $_SESSION['buynow'] = [$id => $qty];
    redirect('order.php?action=checkout');
}

// 结算页：填写收货信息并生成订单
if ($action === 'checkout') {
    requireLogin();
    // 优先使用“立即购买”，否则用购物车
    if (!empty($_SESSION['buynow'])) {
        $items = [];
        foreach ($_SESSION['buynow'] as $pid => $qty) {
            $p = $productModel->getById($pid);
            if ($p && $p['stock'] >= $qty) {
                $items[] = ['product_id' => $p['id'], 'title' => $p['title'], 'price' => $p['price'], 'qty' => $qty];
            }
        }
    } else {
        $items = cartDetails($productModel);
    }
    if (empty($items)) {
        flash('没有可结算的商品');
        redirect('cart.php');
    }

    $total = 0;
    foreach ($items as $it) {
        $total += $it['price'] * $it['qty'];
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $address = trim($_POST['address'] ?? '');
        $contact = trim($_POST['contact'] ?? '');
        if (!$address || !$contact) {
            $error = '请填写收货地址和联系方式';
        } else {
            $orderId = $orderModel->create($_SESSION['user_id'], $items, $address, $contact);
            if ($orderId) {
                cartClear();
                unset($_SESSION['buynow']);
                redirect('order.php?action=pay&id=' . $orderId);
            } else {
                $error = '下单失败，请重试';
            }
        }
    }

    $pageTitle = '订单结算';
    require 'views/layout/header.php';
    ?>
    <h2>确认订单</h2>
    <div class="card">
        <?php if (isset($error)): ?><div class="flash error"><?php echo e($error); ?></div><?php endif; ?>
        <table>
            <thead><tr><th>商品</th><th>单价</th><th>数量</th><th>小计</th></tr></thead>
            <tbody>
                <?php foreach ($items as $it): ?>
                    <tr>
                        <td><?php echo e($it['title']); ?></td>
                        <td>￥<?php echo e($it['price']); ?></td>
                        <td><?php echo e($it['qty']); ?></td>
                        <td class="price">￥<?php echo e($it['price'] * $it['qty']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="cart-total" style="justify-content:flex-end;margin-top:12px;">
            合计：<span class="price">￥<?php echo e($total); ?></span>
        </div>
        <form method="post" style="margin-top:14px;max-width:520px;">
            <label>收货地址</label>
            <input type="text" name="address" value="<?php echo e($_POST['address'] ?? ''); ?>" placeholder="如：浙江省杭州市某某区某某路1号" required>
            <label>联系方式</label>
            <input type="text" name="contact" value="<?php echo e($_POST['contact'] ?? ''); ?>" placeholder="手机号或微信" required>
            <div style="margin-top:14px;">
                <button class="btn" type="submit">提交订单并支付</button>
                <a class="btn btn-secondary" href="cart.php">返回购物车</a>
            </div>
        </form>
    </div>
    <?php
    require 'views/layout/footer.php';
    exit;
}

// 支付（模拟）
if ($action === 'pay') {
    requireLogin();
    $id = (int)($_GET['id'] ?? 0);
    $order = $orderModel->getById($id);
    if (!$order || $order['user_id'] != $_SESSION['user_id']) {
        flash('订单不存在');
        redirect('order.php');
    }
    $orderModel->pay($id);
    flash('支付成功，感谢购买！');
    redirect('order.php?action=detail&id=' . $id);
}

// 取消订单（仅未支付）
if ($action === 'cancel') {
    requireLogin();
    $id = (int)($_GET['id'] ?? 0);
    $order = $orderModel->getById($id);
    if ($order && $order['user_id'] == $_SESSION['user_id']) {
        $orderModel->cancel($id);
        flash('订单已取消');
    }
    redirect('order.php');
}

// 订单详情
if ($action === 'detail') {
    requireLogin();
    $id = (int)($_GET['id'] ?? 0);
    $order = $orderModel->getById($id);
    if (!$order || $order['user_id'] != $_SESSION['user_id']) {
        flash('订单不存在');
        redirect('order.php');
    }
    $items = $orderModel->listItems($id);
    $statusText = ['待支付', '已支付', '已取消'][$order['status']];
    $statusColor = ['#fdecea;color:#c62828', '#e6f4ea;color:#1e7e34', '#eeeeee;color:#666'][$order['status']];

    $pageTitle = '订单详情';
    require 'views/layout/header.php';
    ?>
    <h2>订单详情</h2>
    <div class="card">
        <p>订单号：<?php echo e($order['order_no']); ?></p>
        <p>状态：<span class="pill" style="background:<?php echo $statusColor; ?>"><?php echo $statusText; ?></span></p>
        <p>收货地址：<?php echo e($order['address']); ?></p>
        <p>联系方式：<?php echo e($order['contact']); ?></p>
        <p>下单时间：<?php echo e($order['created_at']); ?></p>
        <table>
            <thead><tr><th>商品</th><th>单价</th><th>数量</th><th>小计</th></tr></thead>
            <tbody>
                <?php foreach ($items as $it): ?>
                    <tr>
                        <td><?php echo e($it['title']); ?></td>
                        <td>￥<?php echo e($it['price']); ?></td>
                        <td><?php echo e($it['qty']); ?></td>
                        <td class="price">￥<?php echo e($it['price'] * $it['qty']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="cart-total" style="justify-content:flex-end;">
            实付：<span class="price">￥<?php echo e($order['total']); ?></span>
        </div>
        <div class="row" style="margin-top:12px;">
            <?php if ($order['status'] == 0): ?>
                <a class="btn" href="order.php?action=pay&id=<?php echo $order['id']; ?>">立即支付</a>
                <a class="btn btn-secondary" href="order.php?action=cancel&id=<?php echo $order['id']; ?>"
                   onclick="return confirm('确定取消该订单？')">取消订单</a>
            <?php endif; ?>
            <a class="btn btn-secondary" href="order.php">返回我的订单</a>
        </div>
    </div>
    <?php
    require 'views/layout/footer.php';
    exit;
}

// 我的订单列表
requireLogin();
$pageTitle = '我的订单';
$orders = $orderModel->listByUser($_SESSION['user_id']);

require 'views/layout/header.php';
?>
<h2>我的订单</h2>
<div class="card">
    <table>
        <thead><tr><th>订单号</th><th>金额</th><th>状态</th><th>时间</th><th>操作</th></tr></thead>
        <tbody>
            <?php foreach ($orders as $o): ?>
                <tr>
                    <td><?php echo e($o['order_no']); ?></td>
                    <td class="price">￥<?php echo e($o['total']); ?></td>
                    <td>
                        <span class="pill" style="background:<?php echo ['#fdecea;color:#c62828','#e6f4ea;color:#1e7e34','#eeeeee;color:#666'][$o['status']]; ?>">
                            <?php echo ['待支付','已支付','已取消'][$o['status']]; ?>
                        </span>
                    </td>
                    <td class="muted"><?php echo e($o['created_at']); ?></td>
                    <td>
                        <a class="btn btn-secondary" href="order.php?action=detail&id=<?php echo $o['id']; ?>">详情</a>
                        <?php if ($o['status'] == 0): ?>
                            <a class="btn" href="order.php?action=pay&id=<?php echo $o['id']; ?>">支付</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($orders)): ?><tr><td colspan="5" class="muted">暂无订单，去 <a href="product.php">商城</a> 逛逛吧</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>
<?php require 'views/layout/footer.php'; ?>
