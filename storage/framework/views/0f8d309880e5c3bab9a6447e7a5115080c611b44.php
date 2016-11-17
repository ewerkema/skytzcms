<div class="news-wrapper">
    <?php if(isset($_GET['article'])): ?>
        <?php ($article = Article::find($_GET['article'])); ?>
        <div class="portlet">
            <h1><?php echo e($article->title); ?></h1>
            <p><i>Gepubliceerd: <?php echo e($article->created_at->diffForHumans()); ?> (<?php echo e($article->created_at->toDateString()); ?>)</i></p>
            <?php echo $article->body; ?>

            <button href="#" onclick="window.history.back();">Ga terug</button>
            <hr>
        </div>
    <?php else: ?>
        <div id="news-<?php echo e($id); ?>">
            <h1>Nieuws: <?php echo e(ArticleGroup::find($id)->title); ?></h1>
            <?php $__currentLoopData = ArticleGroup::find($id)->articles()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                <div class="portlet">
                    <h3 class="newsheader"><?php echo e($article->title); ?></h3>
                    <p><i>Gepubliceerd: <?php echo e($article->created_at->diffForHumans()); ?> (<?php echo e($article->created_at->toDateString()); ?>)</i></p>
                    <p><?php echo e($article->summary); ?></p>
                    <a href="?article=<?php echo e($article->id); ?>">Lees verder</a>
                    <hr>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
        </div>
    <?php endif; ?>
</div>