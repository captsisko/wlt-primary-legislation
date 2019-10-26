<?php

namespace Drupal\wlt\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class LegislationForm.
 */
class LegislationForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'legislation_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    if( !$form_state->has('page_num') ) {
      $form_state->set('page_num', 0);
    }
    $page_num = $form_state->get('page_num');
    $xml = $this->fetchXML();
    $ns = $xml->getNamespaces(true)['ukm'];

    $form = [];
    $form["title_{$page_num}"] = [
      '#type' => 'markup',
      '#markup' =>  "<div><label><b>Title:</b> </label><a href='{$xml->entry[$page_num]->id}' target='_blank'>{$xml->entry[$page_num]->title}</a></div>"
    ];
    $form["{number_{$page_num}"] = [
      '#type' => 'markup',
      '#markup' =>  "<div><label><b>Number:</b> </label>{$xml->entry[$page_num]->children($ns)->Number->attributes()->Value}</div>"
    ];
    $form["{year_{$page_num}"] = [
      '#type' => 'markup',
      '#markup' =>  "<div><label><b>Year:</b> </label>{$xml->entry[$page_num]->children($ns)->Year->attributes()->Value}</div>"
    ];
    $form["{summary_{$page_num}"] = [
      '#type' => 'markup',
      '#markup' =>  "<div><label><b>Summary:</b> </label>{$xml->entry[$page_num]->summary}</div>"
    ];

    // controls paging backwards using the 'previous' button
    if( $page_num <= 0 )
      $next_state = true;
    else
      $next_state = false;

    $form['previous'] = [
      '#type' => 'submit',
      '#value' => $this->t('Previous'),
      '#submit' => ['::fapiWLTMultistepFormPrevSubmit'],
      '#disabled'  => $next_state
    ];

    // controls paging forwards using the 'next' button
    if( $page_num < \sizeof($xml->entry)-1 )
      $next_state = false;
    else
      $next_state = true;
    
    $form['next'] = [
      '#type' => 'submit',
      '#value' => $this->t('Next'),
      '#submit' => ['::fapiWLTMultistepFormNextSubmit'],
      '#disabled'  => $next_state
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {}

  public function fapiWLTMultistepFormNextSubmit(array &$form, FormStateInterface $form_state) {
    // Display result.
    $page_state = $form_state->get('page_num');
    $page_state += 1;
    $form_state->set('page_num', $page_state)->setRebuild(TRUE);
  }

  public function fapiWLTMultistepFormPrevSubmit(array &$form, FormStateInterface $form_state) {
    // Display result.
    $page_state = $form_state->get('page_num');
    $page_state -= 1;
    $form_state->set('page_num', $page_state)->setRebuild(TRUE);
  }

  private function fetchXML(){
    $xml = \simplexml_load_file('http://www.legislation.gov.uk/primary/data.feed');
    // $xml = \simplexml_load_string('http://www.legislation.gov.uk/primary/data.feed');
    // $xml = \simplexml_load_file('data.xml');// or die('Failed to load');
    // $xml = \simplexml_load_string('data.xml');// or die('Failed to load');
		// $size = \sizeof($xml->entry);
    // drupal_set_message("Detecting {$size} entries");
    // $ns = $xml->getNamespaces(true);
    // dsm($ns, 'Name Spaces');
    return $xml;
  }

}
