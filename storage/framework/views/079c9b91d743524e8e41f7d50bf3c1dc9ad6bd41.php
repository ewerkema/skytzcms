<nav class="small-12 medium-12 large-8 columns menu">
    <ul>
        <?php $__currentLoopData = Page::getMenuWithSubpages(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
            <li class="<?php echo e((isset($currentPage) && $page->id == $currentPage->id) ? "active" : ""); ?> <?php echo e((isset($page->subpages)) ? "has-dropdown" : ""); ?>">

                <a href="<?php echo e(page_url($page->getSlug())); ?>"><?php echo e($page->title); ?></a>

                <?php if(isset($page->subpages)): ?>
                    <ul class="dropdown">
                        <?php $__currentLoopData = $page->subpages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subpage): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                            <li class="<?php echo e((isset($currentPage) && $subpage->id == $currentPage->id) ? "active" : ""); ?>">
                                <a href="<?php echo e(page_url($subpage->getSlug())); ?>"><?php echo e($subpage->title); ?></a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                    </ul>
                <?php endif; ?>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
    </ul>
</nav>