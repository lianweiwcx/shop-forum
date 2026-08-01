<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once 'core/functions.php';
require_once 'models/Product.php';
require_once 'models/Post.php';
require_once 'models/Merchant.php';
require_once 'models/Banner.php';

$pageTitle = '首页';
$productModel = new Product();
$postModel = new Post();
$merchantModel = new Merchant();
$bannerModel = new Banner();

$banners  = $bannerModel->listActive();
$products = array_slice($productModel->listOnSale(), 0, 8);
$posts    = array_slice($postModel->listAll(), 0, 6);

// 数据看板：平台入驻商家 / 在售商品 / 社区帖子
$statMerchants = $merchantModel->countByStatus(1);
$statProducts  = $productModel->countOnSale();
$statPosts     = $postModel->countAll();

require 'views/layout/header.php';
?>

<!-- 轮播图 -->
<div class="banner">
    <?php if (empty($banners)): ?>
        <a class="slide active" href="product.php"
           style="background:linear-gradient(120deg,#6d5efc,#00d4ff);display:flex;align-items:center;justify-content:center;color:#fff;font-size:24px;font-weight:700;height:460px;">
            AI 商品服务商城 · 智能社区，等你来逛
        </a>
    <?php else: ?>
        <?php foreach ($banners as $i => $b): ?>
            <a class="slide <?php echo $i === 0 ? 'active' : ''; ?>"
               href="<?php echo e($b['link'] ?: 'product.php'); ?>"
               style="background-image:url('<?php echo e($b['image']); ?>');">
                <span class="banner-cap"><?php echo e($b['title']); ?></span>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if (count($banners) > 1): ?>
        <button class="banner-arrow prev" onclick="bannerGo(-1)">‹</button>
        <button class="banner-arrow next" onclick="bannerGo(1)">›</button>
        <div class="banner-dots">
            <?php foreach ($banners as $i => $b): ?>
                <span class="dot <?php echo $i === 0 ? 'active' : ''; ?>" onclick="bannerTo(<?php echo $i; ?>)"></span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- 数据看板 -->
<div class="stats">
    <div class="stat">
        <div class="num"><?php echo $statMerchants; ?></div>
        <div class="label">AI 商家</div>
    </div>
    <div class="stat">
        <div class="num"><?php echo $statProducts; ?></div>
        <div class="label">AI 商品</div>
    </div>
    <div class="stat">
        <div class="num"><?php echo $statPosts; ?></div>
        <div class="label">AI 帖子</div>
    </div>
</div>

<!-- 快捷入口 -->
<div class="quick">
    <a class="btn" href="product.php">去逛 AI 商城</a>
    <a class="btn btn-secondary" href="community.php">进入 AI 社区</a>
    <a class="btn btn-secondary" href="cart.php">我的购物车</a>
</div>

<h2>精选 AI 商品</h2>
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
    <?php if (empty($products)): ?><p class="muted">暂无商品</p><?php endif; ?>
</div>

<h2>AI 社区热帖</h2>
<div class="card">
    <ul style="margin:0;padding-left:18px;">
        <?php foreach ($posts as $pt): ?>
            <li style="margin:6px 0;">
                <a href="community.php?action=detail&id=<?php echo $pt['id']; ?>"><?php echo e($pt['title']); ?></a>
                <span class="muted">— <?php echo e($pt['nickname']); ?></span>
            </li>
        <?php endforeach; ?>
        <?php if (empty($posts)): ?><li class="muted">暂无帖子</li><?php endif; ?>
    </ul>
</div>

<script>
let bannerIdx = 0;
let bannerTimer = null;
const slides = document.querySelectorAll('.banner .slide');
const dots = document.querySelectorAll('.banner-dots .dot');
function showBanner(i) {
    if (!slides.length) return;
    bannerIdx = (i + slides.length) % slides.length;
    slides.forEach((s, k) => s.classList.toggle('active', k === bannerIdx));
    dots.forEach((d, k) => d.classList.toggle('active', k === bannerIdx));
}
function bannerGo(step) { showBanner(bannerIdx + step); restartTimer(); }
function bannerTo(i) { showBanner(i); restartTimer(); }
function restartTimer() {
    clearInterval(bannerTimer);
    if (slides.length > 1) bannerTimer = setInterval(() => showBanner(bannerIdx + 1), 4000);
}
restartTimer();
</script>
<?php require 'views/layout/footer.php'; ?>
