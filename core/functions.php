<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */

// 公共函数：会话、跳转、登录态、输出转义、提示信息
session_start();

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/../models/User.php';

function redirect($url)
{
    header("Location: $url");
    exit;
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function currentUser()
{
    if (!isLoggedIn()) {
        return null;
    }
    static $user = null;
    if ($user === null) {
        $model = new User();
        $user = $model->getById($_SESSION['user_id']);
    }
    return $user;
}

function requireLogin()
{
    if (!isLoggedIn()) {
        redirect('auth.php?action=login');
    }
}

function requireRole($role)
{
    requireLogin();
    $u = currentUser();
    if (!$u || $u['role'] < $role) {
        redirect('index.php');
    }
}

// HTML 转义，防止 XSS
function e($str)
{
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

// 处理单图上传，返回保存路径（失败返回 null）
function handleUpload($fileKey, $subDir = '')
{
    if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $f = $_FILES[$fileKey];
    if (!in_array($f['type'], $allowed, true) || $f['size'] > 2 * 1024 * 1024) {
        return null;
    }
    $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
    $dir = __DIR__ . '/../uploads/' . $subDir;
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    $name = uniqid('', true) . '.' . $ext;
    $dest = $dir . '/' . $name;
    if (move_uploaded_file($f['tmp_name'], $dest)) {
        return 'uploads/' . $subDir . $name;
    }
    return null;
}

// 闪存提示信息
function flash($msg = null)
{
    if ($msg !== null) {
        $_SESSION['flash'] = $msg;
        return;
    }
    if (isset($_SESSION['flash'])) {
        $m = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $m;
    }
    return null;
}

// 读取网站设置（后台配置，含备案信息等），带静态缓存
function siteSetting($key = null)
{
    static $settings = null;
    if ($settings === null) {
        $settings = [];
        try {
            require_once __DIR__ . '/../models/Setting.php';
            $row = (new Setting())->getAll();
            if ($row) {
                $settings = $row;
            }
        } catch (\Throwable $e) {
            $settings = [];
        }
    }
    if ($key === null) {
        return $settings;
    }
    return $settings[$key] ?? '';
}

// 头像渐变色（根据 seed 取稳定的色相）
function avatarStyle($seed = 0)
{
    $hues = [265, 200, 330, 160, 25, 290, 190, 220];
    $h = $hues[abs((int) $seed) % count($hues)];
    return "linear-gradient(135deg, hsl({$h} 80% 64%), hsl({$h} 85% 48%))";
}

// ---------- 购物车（基于 Session） ----------
function cartAdd($id, $qty = 1)
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $id = (int)$id;
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + max(1, (int)$qty);
}

function cartRemove($id)
{
    unset($_SESSION['cart'][(int)$id]);
}

function cartUpdate($id, $qty)
{
    $id = (int)$id;
    $qty = (int)$qty;
    if ($qty <= 0) {
        unset($_SESSION['cart'][$id]);
    } else {
        $_SESSION['cart'][$id] = $qty;
    }
}

function cartClear()
{
    $_SESSION['cart'] = [];
}

function cartCount()
{
    return isset($_SESSION['cart']) ? (int)array_sum($_SESSION['cart']) : 0;
}

// 根据 Session 购物车还原商品明细（含数量）
function cartDetails($productModel)
{
    $items = [];
    foreach ($_SESSION['cart'] ?? [] as $pid => $qty) {
        $p = $productModel->getById($pid);
        if ($p) {
            $p['qty'] = $qty;
            $p['subtotal'] = $p['price'] * $qty;
            $items[] = $p;
        }
    }
    return $items;
}
