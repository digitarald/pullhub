<div class="span-15 first colborder">
	<ul class="listview" id="listview-<?= $repo['name'] ?>">
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
					echo str_repeat('<ul class="hide">', $diff);
				}
			}

			$cls = 'type-' . $leave['type'];

			if ($leave['type'] == 'tree') {
				$cls .= ' closed';
			}
			if (isset($leave['nature'])) {
				$cls .= ' ' . 'nature-' . $leave['nature'];
			}
		?>
		<li>
			<label class="<?= $cls ?>" title="<?= $path ?>">
				<?php if ($leave['type'] == 'blob'): ?>
				<input type="checkbox" class="pull[]" value="<?= $path ?>" />
				<?php endif; ?>
				<span class="title"><?= $leave['name'] ?></span>

				<?php if (isset($leave['manifest'])): ?>
				<input type="hidden" value="<?= str_replace(array("'", '"'), array('\\\'', "'"), json_encode($leave['manifest'])) ?>" />
				<span class="small quiet"><?= htmlspecialchars($leave['manifest']['description']) ?></span>
				<?php endif; ?>
			</label>
	<?php
		$previous = $leave;
	endforeach;
	?>
		</li>
	</ul>
</div>

<div class="span-7 last">
	<h4>Manifest</h4>
	<dl>
	<?php foreach ($repo['manifest'] as $key => $value): ?>
		<dt><?= $key ?></dt>
		<dd><?= is_array($value) ? htmlspecialchars(print_r($value, true)) : htmlspecialchars($value) ?></dd>
	<?php endforeach; ?>
	</dl>

</div>
