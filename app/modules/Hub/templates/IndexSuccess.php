<ul id="repos">
	<?php foreach ($repos as $repo): ?>
	<li>
		<div class="push-0">
			<?php if (isset($repo['watchers'])): ?>
			<span class="ss_sprite ss_arrow_divide" title="Forks"><?= $repo['forks'] ?></span>
			<span class="ss_sprite ss_magnifier" title="Watchers"><?= $repo['watchers'] ?></span>
			<?php endif; ?>
		</div>
		<h3>
			<a href="<?= $ro->gen('hub.package.view', array('user' => $repo['owner'], 'repo' => $repo['name'])) ?>"><?= $repo['name'] ?></a>
		</h3>
		<p>
			<?php if (isset($repo['manifest']['author'])): ?>
			<span class="alt"><?= (isset($repo['fork']) && $repo['fork']) ? 'Forked by' : 'By' ?></span> <a href="<?= $ro->gen('hub.index', array('user' => $repo['owner'])) ?>"><?= $repo['manifest']['author'] ?></a>.
			<?php endif; ?>
			<?php if (isset($repo['manifest']['description'])): ?>
			<?= $repo['manifest']['description'] ?>
			<?php endif; ?>
		</p>
	</li>
	<?php endforeach; ?>
</ul>