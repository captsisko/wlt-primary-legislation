<?php
namespace Drupal\wlt\Controller;
use Drupal\Core\Controller\ControllerBase;

class LegislationController extends ControllerBase{
	public function content(){
		$build = [
			'#markup'=> $this->t('Whaz upppppppp!'),
		];
		return $build;
	}
}
