<?php defined("C5_EXECUTE") or die("Access Denied."); ?>

<script type="text/javascript">
    var CCM_EDITOR_SECURITY_TOKEN = "<?php echo Core::make('helper/validation/token')->generate('editor')?>";
</script>
            <?php
	$core_editor = Core::make('editor');
	if (method_exists($core_editor, 'outputStandardEditorInitJSFunction')) {
		/* @var $core_editor \Concrete\Core\Editor\CkeditorEditor */
		?>
		<script type="text/javascript">var blockDesignerEditor = <?php echo $core_editor->outputStandardEditorInitJSFunction(); ?>;</script>
	<?php
	} else {
	/* @var $core_editor \Concrete\Core\Editor\RedactorEditor */
	if(method_exists($core_editor, 'requireEditorAssets')){
		$core_editor->requireEditorAssets();
	} ?>
		<script type="text/javascript">
			var blockDesignerEditor = function (identifier) {$(identifier).redactor(<?php echo json_encode(array('plugins' => ['concrete5magic'] + $core_editor->getPluginManager()->getSelectedPlugins(), 'minHeight' => 300,'concrete5' => array('filemanager' => $core_editor->allowFileManager(), 'sitemap' => $core_editor->allowSitemap()))); ?>).on('remove', function () {$(this).redactor('core.destroy');});};
		</script>
		<?php
	} ?><?php $repeatable_container_id = 'btTallacmansCv-cv-container-' . $identifier_getString; ?>
    <div id="<?php echo $repeatable_container_id; ?>">
        <div class="sortable-items-wrapper">
            <a href="#" class="btn btn-primary add-entry">
                <?php echo t('Add Entry'); ?>
            </a>

            <div class="sortable-items" data-attr-content="<?php echo htmlspecialchars(
                json_encode(
                    [
                        'items' => $cv_items,
                        'order' => array_keys($cv_items),
                    ]
                )
            ); ?>">
            </div>

            <a href="#" class="btn btn-primary add-entry add-entry-last">
                <?php echo t('Add Entry'); ?>
            </a>
        </div>

        <script class="repeatableTemplate" type="text/x-handlebars-template">
            <div class="sortable-item" data-id="{{id}}">
                <div class="sortable-item-title">
                    <span class="sortable-item-title-default">
                        <?php echo t('Curriculum Vitae') . ' ' . t("row") . ' <span>#{{id}}</span>'; ?>
                    </span>
                    <span class="sortable-item-title-generated"></span>
                </div>

                <div class="sortable-item-inner">            <div class="form-group">
    <label for="<?php echo $view->field('cv'); ?>[{{id}}][position]" class="control-label"><?php echo t("Postition"); ?></label>
    <?php echo isset($btFieldsRequired['cv']) && in_array('position', $btFieldsRequired['cv']) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <input name="<?php echo $view->field('cv'); ?>[{{id}}][position]" id="<?php echo $view->field('cv'); ?>[{{id}}][position]" class="form-control" type="text" value="{{ position }}" maxlength="255" placeholder="postition" />
</div>            <div class="form-group">
    <label for="<?php echo $view->field('cv'); ?>[{{id}}][employer]" class="control-label"><?php echo t("Employer"); ?></label>
    <?php echo isset($btFieldsRequired['cv']) && in_array('employer', $btFieldsRequired['cv']) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <input name="<?php echo $view->field('cv'); ?>[{{id}}][employer]" id="<?php echo $view->field('cv'); ?>[{{id}}][employer]" class="form-control" type="text" value="{{ employer }}" maxlength="255" placeholder="employer" />
</div>            <div class="form-group">
    <label for="<?php echo $view->field('cv'); ?>[{{id}}][summary]" class="control-label"><?php echo t("Summary"); ?></label>
    <?php echo isset($btFieldsRequired['cv']) && in_array('summary', $btFieldsRequired['cv']) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <textarea name="<?php echo $view->field('cv'); ?>[{{id}}][summary]" id="<?php echo $view->field('cv'); ?>[{{id}}][summary]" class="ft-cv-summary">{{ summary }}</textarea>
</div>            <div class="form-group">
    <label for="<?php echo $view->field('cv'); ?>[{{id}}][dateStart]" class="control-label"><?php echo t("Start Date"); ?></label>
    <?php echo isset($btFieldsRequired['cv']) && in_array('dateStart', $btFieldsRequired['cv']) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <input name="<?php echo $view->field('cv'); ?>[{{id}}][dateStart]" id="<?php echo $view->field('cv'); ?>[{{id}}][dateStart]" class="ft-dateTime-tallacmans_cv-cv-dateStart" type="text" value="{{formatDate dateStart 'MM/DD/YYYY'}}" autocomplete="off" />
</div>            <div class="form-group">
    <label for="<?php echo $view->field('cv'); ?>[{{id}}][dateEnd]" class="control-label"><?php echo t("End Date"); ?></label>
    <?php echo isset($btFieldsRequired['cv']) && in_array('dateEnd', $btFieldsRequired['cv']) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <input name="<?php echo $view->field('cv'); ?>[{{id}}][dateEnd]" id="<?php echo $view->field('cv'); ?>[{{id}}][dateEnd]" class="ft-dateTime-tallacmans_cv-cv-dateEnd" type="text" value="{{formatDate dateEnd 'MM/DD/YYYY'}}" autocomplete="off" />
</div></div>

                <span class="sortable-item-collapse-toggle"></span>

                <a href="#" class="sortable-item-delete" data-attr-confirm-text="<?php echo t('Are you sure'); ?>">
                    <i class="fa fa-times"></i>
                </a>

                <div class="sortable-item-handle">
                    <i class="fa fa-sort"></i>
                </div>
            </div>
        </script>
    </div>

<script type="text/javascript">
    Concrete.event.publish('btTallacmansCv.cv.edit.open', {id: '<?php echo $repeatable_container_id; ?>'});
    $.each($('#<?php echo $repeatable_container_id; ?> input[type="text"].title-me'), function () {
        $(this).trigger('keyup');
    });
</script>