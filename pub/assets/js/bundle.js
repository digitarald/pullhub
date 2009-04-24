
var pullhub = {
	
	ready: function() {
		this.beautifyTree();
	},

	beautifyTree: function() {
		var tree = $('tree');
		if (!tree) return;
		
		var files = new Hash();
		
		tree.getElements('label.nature-source').each(function(file) {
			var manifest = file.getElement('input[type=hidden]');
			if (manifest && (manifest = JSON.decode(manifest.value, false))) {
				file.addEvent('checked', function() {
					if (!manifest.require_regex) return;
					console.log(manifest.require_regex);
					var test = new RegExp(manifest.require);
					files.each(function(label, path) {
						console.log(path, test.test(path));
						if (test.test(path) && !label.hasClass('checked')) {
							label.getElement('input[type=checkbox]').set('checked', true).fireEvent('click');
						}
					});
				});
			}
			
			files.set(file.title, file);
		});
				
		tree.getElements('input[type=checkbox]').addEvent('click', function() {
			var checked = this.checked;
			var toggle = (checked) ? 'addClass' : 'removeClass';
			
			this.stack = this.getParent()[toggle]('checked')
				.getParent().highlight()
				.getElements('input[type=checkbox]')
				.filter(function(box) {
					return box.checked != checked;
				})
				.set('checked', checked)
				.getParent()[toggle]('checked');
			
			this.stack.push(this.getParent());
						
			if (checked) this.stack.fireEvent('checked');
		});
	}

};