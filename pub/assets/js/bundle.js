
var pullhub = {
	
	ready: function() {
		this.beautifyTree();
	},

	beautifyTree: function() {
		var tree = $('tree');
		if (!tree) return;
		
		var sources = new Hash();
		var assets = new Hash();
		
		tree.getElements('label.nature-source').each(function(file) {
			var manifest = file.getElement('input[type=hidden]');
			
			if (manifest && (manifest = JSON.decode(manifest.value, false))) {
				
				if (manifest.require_regex) manifest.require_regex = new RegExp(manifest.require_regex);
				if (manifest.provide_regex) manifest.provide_regex = new RegExp(manifest.provide_regex);
				file.store('manifest', manifest);
				
				file.addEvent('check', function() {
					if (manifest.require_regex) {
						sources.each(function(label, path) {
							if (manifest.require_regex.test(path) && !label.hasClass('checked')) {
								label.getElement('input[type=checkbox]').set('checked', true).fireEvent('click');
							}
						});
					}
					if (manifest.provide_regex) {
						assets.each(function(label, path) {
							if (manifest.provide_regex.test(path) && !label.hasClass('checked')) {
								label.getElement('input[type=checkbox]').set('checked', true).fireEvent('click');
							}
						});
					}
				});
				
				file.addEvent('uncheck', function() {
					var path = file.title;
					sources.each(function(label) {
						var current = label.retrieve('manifest');
						if (current && current.require_regex && current.require_regex.test(path) && label.hasClass('checked')) {
							label.getElement('input[type=checkbox]').set('checked', false).fireEvent('click');
						}
					});
				});
			}
			
			sources.set(file.title, file);
		});

		tree.getElements('label.nature-assets').each(function(file) {

			file.addEvent('uncheck', function() {
				var path = file.title;
				sources.each(function(label) {
					var current = label.retrieve('manifest');
					if (current && current.require_regex && current.require_regex.test(path) && label.hasClass('checked')) {
						label.getElement('input[type=checkbox]').set('checked', false).fireEvent('click');
					}
				});
			});
			
			assets.set(file.title, file);
		});
		
				
		tree.getElements('input[type=checkbox]').addEvent('click', function() {
			var checked = this.checked;
			var toggle = (checked) ? 'addClass' : 'removeClass';
			
			var stack = this.getParent()[toggle]('checked')
				.getParent().highlight()
				.getElements('input[type=checkbox]')
				.filter(function(box) {
					return box.checked != checked;
				})
				.set('checked', checked)
				.getParent()[toggle]('checked');
			
			stack.push(this.getParent());
						
			stack.fireEvent((checked) ? 'check' : 'uncheck');
		}).set('checked', false);
	}

};