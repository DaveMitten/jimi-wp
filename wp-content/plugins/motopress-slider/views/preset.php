<?php if (!defined('ABSPATH')) exit; ?>
<?php
$styleOptions = $this->options;

$btnDisabled = '';
$presetsTableDisabled = '';
?>

<div id="mpsl-style-editor-modal">
    <div id="mpsl-style-editor-wrapper" class="mpsl-style-editor-wrapper">

	    <div class="mpsl-style-editor-content">
			<table>
				<tr>
					<td>
						<div id="mpsl-style-editor-preview-area">
							<div class="mpsl-style-editor-preview">
								Sample Text
							</div>
							<div class="mpsl-style-editor-bg-toggle"></div>
						</div>

				        <div id="mpsl-style-editor-settings-wrapper" class="mpsl-style-editor-settings-wrapper">
				            <?php if (!empty($styleOptions)) { ?>

					            <div id="mpsl-style-mode-switcher">
						            <input type="radio" id="mpsl_style_mode_switcher_style" name="mpsl_style_mode_switcher" data-mode="style" />
						            <label for="mpsl_style_mode_switcher_style"><?php _e('Normal state', MPSL_TEXTDOMAIN) ?></label>

						            <input type="radio" id="mpsl_style_mode_switcher_hover" name="mpsl_style_mode_switcher" data-mode="hover" />
						            <label for="mpsl_style_mode_switcher_hover"><?php _e('Hover state', MPSL_TEXTDOMAIN) ?></label>
					            </div>

					            <div data-group="font-typography" class="mpsl-preset-allow-style-wrapper">
						            <div class="mpsl-option-wrapper">
							            <?php MPSLOptionsFactory::addControl($styleOptions['font-typography']['options']['allow_style']); ?>
						            </div>
					            </div>

				                <ul>
				                    <?php foreach ($styleOptions as $groupKey => $optionsValue) { ?>
				                        <li><a href="#mpsl-tab-<?php echo $groupKey ?>"><?php echo $optionsValue['title'] ?></a></li>
				                    <?php } ?>
				                </ul>
				                <?php
				                foreach ($styleOptions as $groupKey => $optionsValue) { ?>
				                    <div id="mpsl-tab-<?php echo $groupKey ?>">
				                        <table class="form-table">
				                            <tbody>
				                                <?php foreach ($optionsValue['options'] as $optionName => $option) { ?>
					                                <?php if ($optionName === 'allow_style') continue; ?>
				                                    <tr class="mpsl-option-wrapper <?php echo ($option['type'] === 'hidden') ? 'mpsl-option-wrapper-hidden' : ''; ?>">
				                                        <?php if (isset($option['label'])) { ?>
				                                            <th>
				                                                <?php MPSLOptionsFactory::addLabel($option); ?>
				                                            </th>
				                                            <td data-group="<?php echo $groupKey; ?>">
				                                                <?php MPSLOptionsFactory::addControl($option); ?>
				                                            </td>
				                                        <?php } else { ?>
				                                            <th data-group="<?php echo $groupKey; ?>" colspan="2" class="th-full">
				                                                <?php MPSLOptionsFactory::addControl($option); ?>
				                                            </th>
				                                        <?php } ?>
				                                    </tr>
				                                <?php } ?>
				                            </tbody>
				                        </table>
				                    </div>
				                <?php }
				            } ?>
				        </div>

					</td>
					<td>

					    <div id="mpsl-layer-preset-list-wrapper">
							<div id="mpsl-layer-preset-list-child-wrapper">
								<table class="widefat mpsl-layer-presets-table <?php echo $presetsTableDisabled; ?>">
									<tbody></tbody>
								</table>
							</div>
					    </div>

					</td>
				</tr>
			</table>
	    </div>

	    <div class="mpsl-style-editor-footer">
		    <fieldset>
		        <button id="mpsl-apply-layer-preset" class="button-primary" <?php echo $btnDisabled; ?>><?php _e('Apply style', MPSL_TEXTDOMAIN); ?></button>
		        <button id="mpsl-save-as-layer-preset" class="button-secondary" <?php echo $btnDisabled; ?>><?php _e('Duplicate', MPSL_TEXTDOMAIN); ?></button>
		        <button id="mpsl-create-layer-preset" class="button-secondary" <?php echo $btnDisabled; ?>><?php _e('Create new preset', MPSL_TEXTDOMAIN); ?></button>
		    </fieldset>
		    <?php
		    
		    ?>
	    </div>

	</div>
</div>