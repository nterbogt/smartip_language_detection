<?php

namespace Drupal\smartip_language_detection\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form for displaying the Smart IP language detection settings.
 */
class SmartIpLanguageDetectionForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'smartip_language_detection.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'smartip_language_detection_configure_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('smartip_language_detection.settings');

    $form['heading'] = [
      '#type' => 'html_tag',
      '#tag' => 'h2',
      '#value' => $this->t('Smart IP language detection settings'),
    ];

    $form['redirect'] = [
      '#type' => 'checkbox',
      '#title' => 'Redirect to the language path',
      '#default_value' => $config->get('redirect'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('smartip_language_detection.settings');
    $config->set('redirect', $form_state->getValue('redirect'));
    $config->save();

    parent::submitForm($form, $form_state);
  }

}
