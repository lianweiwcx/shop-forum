<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../models/Setting.php';

$pageTitle   = '网站设置';
$settingModel = new Setting();
$settings     = $settingModel->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settingModel->save([
        'site_name'     => trim($_POST['site_name']     ?? ''),
        'site_slogan'   => trim($_POST['site_slogan']   ?? ''),
        'icp'           => trim($_POST['icp']           ?? ''),
        'copyright'     => trim($_POST['copyright']     ?? ''),
        'contact_email' => trim($_POST['contact_email'] ?? ''),
        'contact_phone' => trim($_POST['contact_phone'] ?? ''),
        'about'         => trim($_POST['about']         ?? ''),
    ]);
    flash('网站设置已保存');
    redirect('settings.php');
}

require 'layout/header.php';
?>
<h2>网站设置</h2>
<p class="muted">此处内容会展示在前台页脚（含备案信息），并可自定义站点名称与标语。</p>
<div class="card" style="max-width:720px;">
    <form method="post">
        <label>网站名称</label>
        <input type="text" name="site_name" value="<?php echo e($settings['site_name'] ?? ''); ?>" required>

        <label>网站标语 / 副标题</label>
        <input type="text" name="site_slogan" value="<?php echo e($settings['site_slogan'] ?? ''); ?>" placeholder="一句话介绍你的站点">

        <label>备案号（ICP / 公网安备等）</label>
        <input type="text" name="icp" value="<?php echo e($settings['icp'] ?? ''); ?>" placeholder="如：京ICP备12345678号-1">

        <label>版权信息</label>
        <input type="text" name="copyright" value="<?php echo e($settings['copyright'] ?? ''); ?>" placeholder="© 2026 你的站点">

        <label>联系邮箱</label>
        <input type="text" name="contact_email" value="<?php echo e($settings['contact_email'] ?? ''); ?>" placeholder="admin@example.com">

        <label>联系电话</label>
        <input type="text" name="contact_phone" value="<?php echo e($settings['contact_phone'] ?? ''); ?>" placeholder="400-xxx-xxxx">

        <label>关于我们 / 站点简介</label>
        <textarea name="about" style="min-height:90px;"><?php echo e($settings['about'] ?? ''); ?></textarea>

        <button class="btn" type="submit" style="margin-top:16px;">保存设置</button>
    </form>
</div>
<?php require 'layout/footer.php'; ?>
