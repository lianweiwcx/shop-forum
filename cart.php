<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once 'core/functions.php';
require_once 'models/Product.php';

$productModel = new Product();

// 修改数量 / 删除 / 清空
if (isset($_GET['op'])) {
    requireLogin();
    if ($_GET['op'] === 'update') {
        cartUpdate((int)($_GET['id'] ?? 0), (int)($_GET['qty'] ?? 0));
    } elseif ($_GET['op'] === 'remove') {
        cartRemove((int)($_GET['id'] ?? 0));
    } elseif ($_GET['op'] === 'clear') {
        cartClear();
    }
    redirect('cart.php');
}

$pageTitle = '购物车';
$items = cartDetails($productModel);
$total = 0;
foreach ($items as $it) {
    $total += $it['subtotal'];
}

require 'views/layout/header.php';
?>
<h2>我的购物车</h2>

<?php if (empty($items)): ?>
    <div class="card empty">
        <p class="muted">购物车还是空的</p>
        <a class="btn" href="product.php">去逛商城</a>
    </div>
<?php else: ?>
    <div class="card">
        <table>
            <thead>
                <tr><th>商品</th><th>单价</th><th>数量</th><th>小计</th><th>操作</th></tr>
            </thead>
            <tbody>
                <?php foreach ($items as $it): ?>
                    <tr>
                        <td>
                            <div class="cart-prod">
                                <img src="<?php echo e($it['image'] ?: 'assets/css/style.css'); ?>" alt=""
                                     onerror="this.style.display='none'">
                                <div>
                                    <a href="product.php?action=detail&id=<?php echo $it['id']; ?>"><?php echo e($it['title']); ?></a>
                                    <div class="muted"><?php echo e($it['shop_name'] ?: '店铺'); ?></div>
                                </div>
                            </div>
                        </td>
                        <td>￥<?php echo e($it['price']); ?></td>
                        <td>
                            <input type="number" min="1" max="<?php echo e($it['stock']); ?>"
                                   value="<?php echo e($it['qty']); ?>" style="width:64px;"
                                   onchange="location.href='cart.php?op=update&id=<?php echo $it['id']; ?>&qty='+this.value">
                        </td>
                        <td class="price">￥<?php echo e($it['subtotal']); ?></td>
                        <td>
                            <a class="btn btn-danger" href="cart.php?op=remove&id=<?php echo $it['id']; ?>">删除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="cart-foot">
            <a class="btn btn-secondary" href="cart.php?op=clear">清空购物车</a>
            <div class="cart-total">合计：<span class="price">￥<?php echo e($total); ?></span></div>
            <a class="btn" href="order.php?action=checkout">去结算</a>
        </div>
    </div>
<?php endif; ?>
<?php require 'views/layout/footer.php'; ?>
