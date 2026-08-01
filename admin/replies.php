<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../models/Reply.php';

$pageTitle = '回复管理';
$replyModel = new Reply();

if (isset($_GET['op'], $_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($_GET['op'] === 'delete') {
        $replyModel->delete($id);
        flash('回复已删除');
    }
    redirect('replies.php');
}

$replies = $replyModel->listAll();

require 'layout/header.php';
?>
<h2>回复管理</h2>
<?php if (empty($replies)): ?>
    <div class="card muted">暂无回复</div>
<?php else: ?>
<table>
    <tr><th>ID</th><th>所属帖子</th><th>回复人</th><th>内容</th><th>时间</th><th>操作</th></tr>
    <?php foreach ($replies as $r): ?>
        <tr>
            <td><?php echo $r['id']; ?></td>
            <td>
                <?php if ($r['title']): ?>
                    <a href="../community.php?action=detail&id=<?php echo $r['post_id']; ?>">
                        <?php echo e(mb_substr($r['title'], 0, 20)); ?>
                    </a>
                <?php else: ?><span class="muted">帖子已删</span><?php endif; ?>
            </td>
            <td><?php echo e($r['nickname']); ?></td>
            <td class="muted"><?php echo e(mb_substr($r['content'], 0, 40)); ?></td>
            <td class="muted"><?php echo e($r['created_at']); ?></td>
            <td>
                <a class="btn btn-danger" href="replies.php?op=delete&id=<?php echo $r['id']; ?>"
                   onclick="return confirm('确定删除该回复？');">删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>
<?php require 'layout/footer.php'; ?>
