<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include '../app/views/includes/header.php'; ?>

    <main class="container">
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="create-post">
                <a href="/posts/create" class="btn btn-primary">Create New Post</a>
            </div>
        <?php endif; ?>

        <div class="posts">
            <?php foreach ($posts as $post): ?>
                <article class="post-card">
                    <header class="post-header">
                        <img src="<?php echo htmlspecialchars($post['profile_image']); ?>" 
                             alt="Profile" class="profile-image">
                        <div class="post-meta">
                            <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                            <span class="post-info">
                                Posted by <?php echo htmlspecialchars($post['username']); ?>
                                on <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                            </span>
                        </div>
                    </header>

                    <div class="post-content">
                        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                        <?php if ($post['image_path']): ?>
                            <img src="<?php echo htmlspecialchars($post['image_path']); ?>" 
                                 alt="Post image" class="post-image">
                        <?php endif; ?>
                    </div>

                    <footer class="post-footer">
                        <div class="vote-section">
                            <span class="vote-score"><?php echo $post['vote_score']; ?></span>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <button class="vote-btn" data-post="<?php echo $post['post_id']; ?>" 
                                        data-vote="1">↑</button>
                                <button class="vote-btn" data-post="<?php echo $post['post_id']; ?>" 
                                        data-vote="-1">↓</button>
                            <?php endif; ?>
                        </div>
                        <span class="comment-count">
                            <?php echo $post['comment_count']; ?> comments
                        </span>
                    </footer>
                </article>
            <?php endforeach; ?>
        </div>
    </main>

    <script src="/js/main.js"></script>
</body>
</html> 