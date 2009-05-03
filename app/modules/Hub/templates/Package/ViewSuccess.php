<div class="span-15 first">
	<h3>Files</h3>

	<ul id="tree">
	<?php
	$previous = null;
	foreach ($repo['tree'] as $path => $leave):
		if ($previous) {
			$diff = $leave['depth'] - $previous['depth'];
			if (!$diff) {
				echo '</li>';
			} elseif ($diff < 0) {
				echo str_repeat('</ul></li>', $diff * -1);
			} elseif ($diff > 0) {
				echo str_repeat('<ul>', $diff);
			}
		}
	?>
		<li>
			<label class="<?= isset($leave['nature']) ? ('nature-' . $leave['nature']) : '' ?>" title="<?= $path ?>">
				<input type="checkbox" class="pull[]" value="<?= implode('/', $leave['path']) ?>" />
				<span class="title"><?= $leave['name'] ?></span>

				<?php if (isset($leave['manifest'])): ?>
				<input type="hidden" value="<?= str_replace('"', "'", json_encode($leave['manifest'])) ?>" />
				<span class="small quiet"><?= $leave['manifest']['description'] ?></span>
				<?php endif; ?>

			</label>
	<?php
		$previous = $leave;
	endforeach;
	?>
		</li>
	</ul>
</div>

<div class="span-8 last">
	<h3>Manifest</h3>

	<dl>
	<?php foreach ($repo['manifest'] as $key => $value): ?>
		<dt><?= $key ?></dt>
		<dd><?= is_array($value) ? print_r($value, true) : $value ?></dd>
	<?php endforeach; ?>
	</dl>

</div>