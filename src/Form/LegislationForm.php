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
    $xml = $this->fetchXML();

    dsm( \sizeof( $xml->entry), 'XML Entry');
    drupal_set_message("TEST: {$xml->xmlns}");

    /* foreach ($xml->entry as $key => $value) {
      // dsm($value->children(), 'Value');
      drupal_set_message("TITLE: {$value->title}");
      // drupal_set_message("YEAR: {$value->children('ukm', true)->Year}");
      drupal_set_message("SUMMARY: {$value->summary}");
    } */

    $form = [];
    $i = 0;
    foreach ($xml->entry as $key => $entry) {
      $form["title_{$i}"] = [
        '#type' => 'markup',
        '#markup' =>  "<div><label><b>Title:</b> </label>{$entry->title}</div>"
      ];
      $form["{summary_{$i}"] = [
        '#type' => 'markup',
        '#markup' =>  "<div><label><b>Summary:</b> </label>{$entry->summary}</div>"
      ];
      $i++;
    }

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      // @TODO: Validate fields.
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Display result.
    foreach ($form_state->getValues() as $key => $value) {
      \Drupal::messenger()->addMessage($key . ': ' . ($key === 'text_format'?$value['value']:$value));
    }
  }

  private function fetchXML(){
    $xml = \simplexml_load_file('http://www.legislation.gov.uk/primary/data.feed');
    // $xml = \simplexml_load_string('http://www.legislation.gov.uk/primary/data.feed');
    // $xml = \simplexml_load_file('data.xml');// or die('Failed to load');
    // $xml = \simplexml_load_string('data.xml');// or die('Failed to load');
		// $size = \sizeof($xml->entry);
		// drupal_set_message("Detecting {$size} entries");
    // dsm($xml, 'Feed');
    return $xml;
  }

}
