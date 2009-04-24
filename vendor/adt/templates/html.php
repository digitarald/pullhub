
<div id="adt-container">
	<h1>ADT Debug Toolbar</h1>

	<div id="adt-sections">
		<h2>Matched Routes</h2>
		<div id="adt-section-routing">
			<?php include('html-routing.php'); ?>			
		</div><!-- routing -->

		<h2>Global Request Data</h2>
		<div id="adt-section-globalrd" >
			<?php include('html-globalrd.php'); ?>
		</div>

		<? if (isset($template['actions'])): ?>
		<h2>Actions</h2>
		<div id="adt-section-actions" >
			<?php include('html-actions.php'); ?>
		</div>
		<?php endif; ?>

		<?php if ( AgaviConfig::get('core.use_translation') ): ?>
		<h2>Translation</h2>
		<div>
			Current locale: <?php echo $template['tm']->getCurrentLocaleIdentifier(); ?>
			<br />
			Default locale: <?php echo $template['tm']->getDefaultLocaleIdentifier(); ?>

			<br /><br />

			Available locales
			<br />
			<ul>
				<?php foreach ( $template['tm']->getAvailableLocales() as $locale ): ?>
				<li>
					<?php echo $locale['parameters']['description']; ?>
					<br />
					Identifier: <?php echo $locale['identifier']; ?>
					<br />
					Language: <?php echo $locale['identifierData']['language'] ?>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endif; ?>

		<h2>Environment</h2>
		<div>
			<dl>
				<dt>Current environment</dt>
				<dd><?php echo AgaviConfig::get('core.environment'); ?></dd>
			</dl>
			
			<div id="adt-section-environments">
			<?php foreach( $template['environments'] as $name => $environment): ?>
				<h3><?php echo $name; ?></h3>
				<div>
					<?php if ( isset($environment['system_actions']) ): ?>
					<h4>System Actions</h4>
					<dl>
						<?php foreach( $environment['system_actions'] as $name => $value ): ?>
						<dt><?php echo $name; ?></dt>
						<dd>
							Module: <?php echo $value['module']; ?>
							<br />
							Action: <?php echo $value['action']; ?>
						</dd>
						<?php endforeach; ?>
					</dl>
					<?php endif; ?>
					
					<?php if ( isset($environment['settings']) ): ?>
					<h4>Settings</h4>
					<ul>
						<?php foreach( $environment['settings'] as $name => $value ): ?>
						<li><?php echo $name; ?>: <?php echo $value; ?></li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>
					
					<?php if ( isset($environment['exception_templates']) ): ?>
					<h4>Exception templates</h4>
					<ul>
						<?php foreach( $environment['exception_templates'] as $exception ): ?>
						<li>Context: <?php echo !empty($exception['context'])?$exception['context']:'default'; ?> <?php echo $exception['template']; ?></li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>
				
				</div>
			<?php endforeach; ?>
			</div>


			<h3>Configuration Directives</h3>
			<div>
				<table>
				<?php $conf = AgaviConfig::toArray(); ksort($conf); foreach($conf as $name => $value): ?>
				<tr>
					<td><pre><?php echo htmlspecialchars($name); ?></pre></td>
					<td><pre><?php echo htmlspecialchars(var_export($value, true)); ?></pre></td>
				</tr>
				<?php endforeach; ?>
				</table>
			</div>

			<h3>Agavi</h3>
			<div>
				<dl>
					<dt>Version:</dt>
					<dd><?php echo AgaviConfig::get('agavi.version'); ?></dd>

					<dt>Location:</dt>
					<dd><?php echo AgaviConfig::get('core.agavi_dir'); ?></dd>
				</dl>
			</div>
		</div>

		<h2>Log</h2>
		
		<div id="adt-section-log">
			<?php if($template['log']): ?>
				<table cellpadding="5" cellspacing="0">			
				<?php foreach($template['log'] as $logLine): ?>
					<tr>
						<td class="timestamp"><?php echo $logLine['microtime']; ?></td>
						<td><?php echo htmlspecialchars($logLine['message']); ?></td>
					</tr>
				<?php endforeach; ?>
				</table>
			<?php else: ?>
				<p>No log lines</p>
			<?php endif;?>
		</div>
		
		<?php if($template['datasources']) foreach($template['datasources'] as $datasource): ?>
			<h2><?php echo htmlspecialchars($datasource->getName()); ?></h2>
			<div>
				<?php if ($datasource->getDataType() == AdtDebugFilterDataSource::TYPE_KEYVALUE): ?>
					<?php foreach($datasource->getData() as $key => $value):
						echo htmlspecialchars($key); ?>: <?php echo htmlspecialchars($value); ?><br/>
					<?php endforeach;
				elseif ($datasource->getDataType() == AdtDebugFilterDataSource::TYPE_LINEAR):
					foreach($datasource->getData() as $line):
						echo htmlspecialchars($line); ?><br/>
					<?php endforeach;
				elseif ($datasource->getDataType() == AdtDebugFilterDataSource::TYPE_TABULAR):
					$table = $datasource->getData(); ?>
					<table>
						<tr>
						<?php foreach($table['headers'] as $value): ?>
							<th><?php echo htmlspecialchars($value); ?></th>
						<?php endforeach;?>
						</tr>
						<?php foreach($table['rows'] as $row): ?>
						<tr>
							<?php foreach($row as $value): ?>
								<td><?php echo htmlspecialchars($value); ?></td>
							<?php endforeach;?>
						</tr>
						<?php endforeach;?>
					</table>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
		
	</div><!-- sections / tabs -->
</div>
