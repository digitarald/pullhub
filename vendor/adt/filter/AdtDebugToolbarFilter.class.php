<?php

/**
 * AdtDebugFirePhpFilter renders AdtDebugFilter's log into a 
 * HTML toolbar and injects it to the response HTML 
 *
 * @author     Daniel Ancuta <daniel.ancuta@whisnet.pl>
 * @author     Veikko Mäkinen <veikko@veikko.fi>
 * @copyright  Authors
 * @version    $Id$
 */
class AdtDebugToolbarFilter extends AdtDebugFilter implements AgaviIActionFilter
{

	public function render(AgaviExecutionContainer $container)
	{
		if (!$this->isAllowedOutputType($container)) {
			return;
		}
		
		$template = $this->rq->getAttributeNamespace(AdtDebugFilter::NS_DATA);
		$template['datasources'] = $this->rq->getAttribute('datasources', AdtDebugFilter::NS, array());

		// TODO: handle relative and absolute paths
		ob_start();
		include(dirname(__FILE__) .'/../'. $this->getParameter('template') );
		$output = ob_get_contents();
		ob_end_clean();

		//
		// FIXME
		// Rewrite this injections to DOM ( http://php.net/manual/en/book.dom.php )
		//

		// Inject AgaviDebugToolbar to response
		$output	= str_replace('</body>', $output."\n</body>", $container->getResponse()->getContent());

		# CSS files
		$cssOutput = '';
		foreach($this->getParameter('css', array()) as $css) {
			$cssOutput .= sprintf('<link rel="stylesheet" type="text/css" href="%s" />',
										$this->getParameter('modpub') . '/' . $css)."\n";
		}

		# JS files
		$jsOutput = '';
		foreach( $this->getParameter('js', array()) as $js ) {
			$jsOutput .= sprintf('<script type="text/javascript" src="%s"></script>',
									 $this->getParameter('modpub').'/'.$js)."\n";
		}

		$output = str_replace('</head>', $cssOutput.$jsOutput."\n</head>", $output);

		$container->getResponse()->setContent($output);
	}
}
?>