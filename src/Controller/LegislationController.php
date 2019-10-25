<?php
namespace Drupal\wlt\Controller;
use Drupal\Core\Controller\ControllerBase;

class LegislationController extends ControllerBase{
	public function content(){
		$xml = \simplexml_load_file('http://www.legislation.gov.uk/primary/data.feed');
		$size = \sizeof($xml->entry);
		drupal_set_message("Detecting {$size} entries");
		dsm($xml);
		
		$build = [
			'#markup'=> $this->t('Whaz upppppppp!'),
		];
		return $build;
	}
}
