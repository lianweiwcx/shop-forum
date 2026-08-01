<?php
/**
 * 作者：海阔HaiKuo
 * 联系：haikuojs@yeah.net
 * 您可以免费和商业使用，但请尊重作者权益
 */
require_once 'core/functions.php';
require_once 'models/Post.php';
require_once 'models/Reply.php';
require_once 'models/Topic.php';

$action = $_GET['action'] ?? 'list';
$postModel   = new Post();
$replyModel  = new Reply();
$topicModel  = new Topic();

// 发布帖子
if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = currentUser();
    if (!$u) { redirect('auth.php?action=login'); }
    $title   = trim($_POST['title']   ?? '');
    $content = trim($_POST['content'] ?? '');
    $topic   = trim($_POST['topic']   ?? '');
    $image   = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = handleUpload($_FILES['image']);
    }
    if ($title && $content && $topic) {
        $postModel->create($u['id'], $title, $content, $topic, $image);
        redirect('community.php');
    } else {
        $err = '请完整填写标题、内容与话题';
    }
}

// 回复
if ($action === 'reply' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = currentUser();
    if (!$u) { redirect('auth.php?action=login'); }
    $postId  = (int)($_POST['post_id'] ?? 0);
    $content = trim($_POST['content'] ?? '');
    if ($postId && $content) {
        $replyModel->create($postId, $u['id'], $content);
    }
    redirect("community.php?action=detail&id=$postId");
}

// 删除帖（作者或管理员）
if ($action === 'delete') {
    $u = currentUser();
    $id = (int)($_GET['id'] ?? 0);
    $post = $postModel->getById($id);
    if ($u && $post && ($u['id'] == $post['user_id'] || $u['role'] == 2)) {
        $postModel->delete($id);
    }
    redirect('community.php');
}

$topics = $topicModel->listAll();
$filter = $_GET['topic'] ?? '';
$posts  = $postModel->listAll($filter);

require 'views/layout/header.php';
?>

<?php if ($action === 'list'): ?>
    <div class="topic-filter">
        <a class="pill <?php echo $filter === '' ? 'active' : ''; ?>" href="community.php">全部</a>
        <?php foreach ($topics as $t): ?>
            <a class="pill <?php echo $filter === $t['name'] ? 'active' : ''; ?>" href="community.php?topic=<?php echo urlencode($t['name']); ?>"><?php echo e($t['name']); ?></a>
        <?php endforeach; ?>
        <?php if (isLoggedIn()): ?>
            <a class="pill" href="community.php?action=create" style="background:var(--primary);color:#fff;">+ 发帖</a>
        <?php endif; ?>
    </div>

    <?php if (empty($posts)): ?>
        <div class="card"><p class="muted">该话题下还没有帖子，快来发第一帖吧。</p></div>
    <?php else: ?>
        <div class="post-list">
            <?php foreach ($posts as $p): ?>
                <a class="post-card" href="community.php?action=detail&id=<?php echo $p['id']; ?>">
                    <div class="post-avatar" style="background:<?php echo avatarStyle($p['user_id'] ?? 0); ?>"><?php echo e(mb_substr($p['nickname'] ?? 'U', 0, 1)); ?></div>
                    <div class="post-main">
                        <div class="post-top">
                            <span class="post-title"><?php echo e($p['title']); ?></span>
                            <span class="pill"><?php echo e($p['topic']); ?></span>
                        </div>
                        <div class="post-excerpt"><?php echo e(mb_substr(strip_tags($p['content']), 0, 60)); ?><?php echo mb_strlen($p['content']) > 60 ? '…' : ''; ?></div>
                        <div class="post-meta">
                            <span><?php echo e($p['nickname']); ?></span>
                            <span>·</span>
                            <span><?php echo e(date('Y-m-d', strtotime($p['created_at']))); ?></span>
                            <span>·</span>
                            <span>💬 <?php echo (int)($p['reply_count'] ?? 0); ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php elseif ($action === 'create'): ?>
    <h2>发布帖子</h2>
    <?php if (isset($err)): ?><p class="muted" style="color:#ef4444;"><?php echo e($err); ?></p><?php endif; ?>
    <div class="card" style="max-width:720px;">
        <form method="post" enctype="multipart/form-data">
            <label>标题</label>
            <input type="text" name="title" required placeholder="一句话说清你想聊什么">
            <label>话题</label>
            <select name="topic" required>
                <option value="">请选择话题</option>
                <?php foreach ($topics as $t): ?>
                    <option value="<?php echo e($t['name']); ?>"><?php echo e($t['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <label>内容</label>
            <textarea name="content" required placeholder="详细描述你的想法、问题或经验…"></textarea>
            <label>配图（可选）</label>
            <input type="file" name="image" accept="image/*">
            <button class="btn" type="submit" style="margin-top:14px;">发布</button>
        </form>
    </div>

<?php elseif ($action === 'detail'):
    $id = (int)($_GET['id'] ?? 0);
    $post = $postModel->getById($id);
    if (!$post) {
        echo '<div class="card"><p class="muted">帖子不存在或已被删除。</p><a href="community.php">返回社区</a></div>';
        require 'views/layout/footer.php';
        exit;
    }
    $replies = $replyModel->listByPost($id);
    $u = currentUser();
    $authorInitial = mb_substr($post['nickname'] ?? 'U', 0, 1);
?>
    <div class="post-detail">
        <a class="back" href="community.php">‹ 返回社区</a>
        <div class="post-detail-head">
            <div class="post-avatar lg" style="background:<?php echo avatarStyle($post['user_id'] ?? 0); ?>"><?php echo e($authorInitial); ?></div>
            <div style="flex:1;min-width:0;">
                <h2><?php echo e($post['title']); ?></h2>
                <div class="post-meta">
                    <span><?php echo e($post['nickname']); ?></span>
                    <span>·</span>
                    <span class="pill"><?php echo e($post['topic']); ?></span>
                    <span>·</span>
                    <span><?php echo e($post['created_at']); ?></span>
                </div>
            </div>
            <?php if ($u && ($u['id'] == $post['user_id'] || $u['role'] == 2)): ?>
                <a class="btn btn-danger btn-sm" href="community.php?action=delete&id=<?php echo $post['id']; ?>" onclick="return confirm('确定删除该帖？');">删除</a>
            <?php endif; ?>
        </div>

        <div class="card post-content">
            <?php if ($post['image']): ?><img src="<?php echo e($post['image']); ?>" class="post-detail-img" alt=""><br><?php endif; ?>
            <?php echo nl2br(e($post['content'])); ?>
        </div>

        <h3 style="margin:10px 0 14px;">回复（<?php echo count($replies); ?>）</h3>
        <div class="reply-list">
            <?php foreach ($replies as $r): ?>
                <div class="reply-card">
                    <div class="post-avatar" style="background:<?php echo avatarStyle($r['user_id'] ?? 0); ?>"><?php echo e(mb_substr($r['nickname'] ?? 'U', 0, 1)); ?></div>
                    <div class="reply-body">
                        <div class="post-meta">
                            <span><?php echo e($r['nickname']); ?></span>
                            <span>·</span>
                            <span><?php echo e($r['created_at']); ?></span>
                        </div>
                        <p style="margin:4px 0 0;"><?php echo nl2br(e($r['content'])); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if (empty($replies)): ?><p class="muted" style="margin-top:12px;">还没有回复，来说两句吧～</p><?php endif; ?>

        <?php if ($u): ?>
            <form method="post" action="community.php?action=reply" class="card" style="margin-top:20px;">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <label>发表回复</label>
                <textarea name="content" required placeholder="友善交流，理性讨论"></textarea>
                <button class="btn" type="submit">回复</button>
            </form>
        <?php else: ?>
            <p style="margin-top:18px;"><a href="auth.php?action=login">登录</a> 后参与回复</p>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php require 'views/layout/footer.php'; ?>
