<div class="container block_box">
    <div class="message_box">
        <p>
            <i class="glyphicon glyphicon-ok-circle"></i><span class="message_text"><?php echo isset($message) ? $message : '操作成功'; ?></span>
        </p>
        <p>
            您现在可以去：
            <?php if ($this->user['user_id']): ?>
                <a class="blue" href="{url:/ucenter/index}">个人中心 >></a>
            <?php endif; ?>

            <a class="blue" href="/">网站首页 >></a>
        </p>
    </div>
</div>
