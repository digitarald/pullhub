<ul id="repos">
	<?php foreach ($repos as $key => $repo): ?>
	<li class="clear">
		<div class="repo-head">
			<div class="push-0">
				<?php if (isset($repo['watchers'])): ?>
				<span class="ss_sprite ss_arrow_divide" title="Forks"><?= $repo['forks'] ?></span>
				<span class="ss_sprite ss_magnifier" title="Watchers"><?= $repo['watchers'] ?></span>
				<?php endif; ?>
			</div>
			<h3>
				<a href="<?= $ro->gen('hub.package.view', array('user' => $repo['owner'], 'repo' => $repo['name'])) ?>" class="toggle"><?= $repo['name'] ?></a>
			</h3>
			<p class="tagline">
				<span class="alt"><?= (isset($repo['fork']) && $repo['fork']) ? 'forked by' : 'by' ?></span>
				<a href="<?= $ro->gen('hub.index', array('user' => $repo['owner'])) ?>"><?= htmlspecialchars(isset($repo['manifest']['author']) ? $repo['manifest']['author'] : $repo['owner'] ) ?></a>.

				<?php if (isset($repo['manifest']['description'])): ?>
				<?= htmlspecialchars($repo['manifest']['description']) ?>
				<?php elseif (isset($repo['description'])): ?>
				<?= htmlspecialchars($repo['description']) ?>
				<?php endif; ?>
			</p>
		</div>
		<div class="more">
			<?= $slot['repo-' . $key] ?>
			<hr class="clear" />
		</div>
	</li>
	<?php endforeach; ?>
</ul>