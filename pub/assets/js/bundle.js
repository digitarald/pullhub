
var pullhub = {
	
	ready: function() {

		$$('#repos a.toggle').each(function(toggle) {
			var more = toggle.getParent('li').getElement('.more')
				.setStyles({'overflow': 'hidden', 'height': 0})
				.set('tween', {onComplete: function() {
					if (more.offsetHeight) more.setStyle('height', 'auto');
				}});
			toggle.addEvent('click', function() {
				var now = more.offsetHeight;
				more.tween('height', now, now ? 0 : more.scrollHeight);
				return false;
			});
		});
	
		
		this.sources = new Hash();
		this.assets = new Hash();
		
		$$('ul.listview').each(this.beautifyTree, this);
		
	},

	beautifyTree: function(tree) {
		var sources = this.sources;
		var assets = this.assets;
		
		var self = this;
		
		tree.getElements('label.nature-source').each(function(file) {
			var manifest = file.getElement('input[type=hidden]');

			if (manifest && (manifest = JSON.decode(manifest.value, false))) {

				if (manifest.require_regex) manifest.require_regex = new RegExp(manifest.require_regex);
				if (manifest.provide_regex) manifest.provide_regex = new RegExp(manifest.provide_regex);
				
				file.store('manifest', manifest);
				
				file.addEvent('check', function(action) {
					if (manifest.require_regex) {
						sources.each(function(label, path) {
							if (manifest.require_regex.test(path) && !label.hasClass('checked')) {
								label.getElement('input[type=checkbox]').set('checked', true).fireEvent('click');
								self.sourcesFound.push(path);
							}
						});
					}
					if (manifest.provide_regex) {
						assets.each(function(label, path) {
							if (manifest.provide_regex.test(path) && !label.hasClass('checked')) {
								label.getElement('input[type=checkbox]').set('checked', true).fireEvent('click');
								self.assetsFound.push(path);
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
							self.sourcesFound.push(path);
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
						self.sourcesFound.push(path);
					}
				});
			});
			
			assets.set(file.title, file);
		});
		
		function startCollection() {
			self.sourcesFound = [];
			self.assetsFound = [];
		}
		function flushCollection() {
			var msg = [];
			
			if (self.sourcesFound.length) msg.push(self.sourcesFound.length + ' Sources:\n  ' + self.sourcesFound.join('\n  '));
			if (self.assetsFound.length) msg.push(self.assetsFound.length + ' Assets:\n  ' + self.assetsFound.join('\n  '));
			
			if (msg.length) alert(msg.join('\n'));
		}
		
		tree.getElements('label.type-tree').addEvents({
			'click': function() {
				if (this.retrieve('timer')) return false;
				
				this.store('timer', (function() {
					this.eliminate('timer');
					this.toggleClass('closed');	
					this.getNext('ul')[(this.hasClass('closed')) ? 'addClass' : 'removeClass']('hide');
				}).delay(200, this));
				
				return false;
			},
			'dblclick': function() {
				var timer = this.retrieve('timer');
				this.eliminate('timer');
				$clear(timer);

				if (window.getSelection) window.getSelection().removeAllRanges();
				
				var item = this.getParent();
				var checked = !!(item.getElement('label.type-blob:not(.checked)'));
				
				startCollection();
				
				item.getElements('input[type=checkbox]')
					.filter(function(box) {
						return box.checked != checked;
					})
					.set('checked', checked)
					.getParent()[(checked) ? 'addClass' : 'removeClass']('checked')
					.fireEvent((checked) ? 'check' : 'uncheck');
				
				item.getElements('ul.hide').removeClass('hide');
				item.getElements('label.closed').removeClass('closed');

				flushCollection();
				
				return false;
			}
		});
		
				
		tree.getElements('input[type=checkbox]').addEvent('click', function(event) {
			
			var label = this.getParent();

			var collect = !!(event);
			if (!collect) {
				this.getParents('ul.hide').each(function(list) {
					list.getPrevious('label').removeClass('closed');
					list.removeClass('hide');
				});
			} else {
				startCollection();
			}
			
			var checked = this.checked;
			
			label[(checked) ? 'addClass' : 'removeClass']('checked')
				.fireEvent((checked) ? 'check' : 'uncheck');
			
			if (collect) flushCollection();
		}).set('checked', false).set('opacity', 0.01);
	}

};