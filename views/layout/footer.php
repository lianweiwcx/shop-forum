<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
?>
<!--
作者：海阔HaiKuo
联系：haikuojs@yeah.net
您可以免费和商业使用，但请尊重作者权益
-->
</div><!-- /.container -->

<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-brand"><?php echo e(siteSetting('site_name') ?: 'AI 商城社区'); ?></div>
        <?php if (siteSetting('site_slogan')): ?><div class="muted"><?php echo e(siteSetting('site_slogan')); ?></div><?php endif; ?>
        <div class="footer-meta muted">
            <?php if (siteSetting('icp')): ?><span>备案号：<a href="https://beian.miit.gov.cn/" target="_blank" rel="noopener noreferrer"><?php echo e(siteSetting('icp')); ?></a></span><?php endif; ?>
            <?php if (siteSetting('contact_email')): ?><span>邮箱：<?php echo e(siteSetting('contact_email')); ?></span><?php endif; ?>
            <?php if (siteSetting('contact_phone')): ?><span>电话：<?php echo e(siteSetting('contact_phone')); ?></span><?php endif; ?>
        </div>
        <div class="muted"><?php echo e(siteSetting('copyright') ?: '© 2026 AI 商城社区'); ?></div>
    </div>
</footer>
</body>
</html>
