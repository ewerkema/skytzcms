<div class="form-wrapper">
    <div id="form-<?php echo e($id); ?>">
        <form role="form" action="/forms/<?php echo e($id); ?>/send" method="POST">
            <input type="hidden" name="form_id" value="<?php echo e($id); ?>">
            <?php echo e(csrf_field()); ?>


            <?php if(session()->has('message')): ?>
                <div data-alert class="alert-box success">
                    <?php echo e(session()->get('message')); ?>

                    <a href="#" class="close">&times;</a>
                </div>
            <?php endif; ?>

            <?php $__currentLoopData = App\Models\Form::find($id)->fields()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $start => $field): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                <div class="row">
                    <div class="large-12 columns">
                        <div class="row">
                            <div class="small-3 columns">
                                <label for="<?php echo e($field->formName()); ?>" class="right inline<?php echo e($errors->has($field->formName()) ? ' error' : ''); ?>"><?php echo e($field->name); ?><?php echo e($field->required ? "*" : ""); ?></label>
                            </div>

                            <div class="small-9 columns">
                                <?php if($field->type == "text" || $field->type == "email" || $field->type == "number"): ?>
                                    <input id="<?php echo e($field->formName()); ?>" type="<?php echo e($field->type); ?>" name="<?php echo e($field->formName()); ?>" placeholder="<?php echo e($field->placeholder); ?>" value="<?php echo e(old($field->formName())); ?>" autofocus>
                                <?php elseif($field->type == "textarea"): ?>
                                    <textarea id="<?php echo e($field->formName()); ?>" name="<?php echo e($field->formName()); ?>" placeholder="<?php echo e($field->placeholder); ?>"><?php echo e(old($field->formName())); ?></textarea>
                                <?php elseif($field->type == "select"): ?>
                                    <select name="<?php echo e($field->formName()); ?>">
                                        <?php $__empty_1 = true; $__currentLoopData = $field->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); $__empty_1 = false; ?>
                                            <option value="<?php echo e($option['value']); ?>"<?php echo e((old($field->formName()) == $option['value']) ? " selected" : ""); ?>>
                                                <?php echo e($option['name']); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); if ($__empty_1): ?>
                                            <option disabled>Geen optie om te selecteren</option>
                                        <?php endif; ?>
                                    </select>
                                <?php elseif($field->type == "radio"): ?>
                                    <?php $__empty_1 = true; $__currentLoopData = $field->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); $__empty_1 = false; ?>
                                        <input type="radio" name="<?php echo e($field->formName()); ?>" id="radio-<?php echo e($option['value']); ?>" value="<?php echo e($option['value']); ?>"<?php echo e((old($field->formName()) == $option['value']) ? " checked" : ""); ?>>
                                        <label for="radio-<?php echo e($option['value']); ?>"><?php echo e($option['name']); ?></label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); if ($__empty_1): ?>
                                        <p>Geen waardes om te selecteren.</p>
                                    <?php endif; ?>
                                <?php elseif($field->type == "checkbox"): ?>
                                    <input type="checkbox" name="<?php echo e($field->formName()); ?>" id="checkbox-<?php echo e($field->formName()); ?>" value="aangevinkt" <?php echo e((old($field->formName()) == "aangevinkt") ? " checked" : ""); ?>>
                                    <label for="checkbox-<?php echo e($field->formName()); ?>"><?php echo e($field['placeholder']); ?></label>
                                <?php endif; ?>

                                <?php if($errors->has($field->formName())): ?>
                                    <small class="error"><?php echo e($errors->first($field->formName())); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
            <div class="form-group">
                <div class="col-md-8 col-md-offset-4">
                    <button type="submit" class="button right" name="submitform">Verzenden</button>
                    <span class="inline right">Velden met een (*) zijn verplicht.</span>
                </div>
            </div>
        </form>
    </div>
</div>