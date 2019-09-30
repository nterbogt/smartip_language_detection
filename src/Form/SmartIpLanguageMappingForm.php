<?php

namespace Drupal\smartip_language_detection\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\smartip_language_detection\Entity\SmartIpLanguageMapping;

/**
 * Class SmartIpLanguageMappingForm.
 *
 * @package Drupal\smartip_language_detection\Form
 */
class SmartIpLanguageMappingForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\smartip_language_detection\Entity\SmartIpLanguageMapping $entity */
    $entity = $this->entity;

    $form['country'] = [
      '#title' => $this->t('Country'),
      '#type' => 'select',
      '#default_value' => $entity->getCountry(),
      '#options' => \Drupal::service('country_manager')->getList(),
      '#required' => TRUE,
    ];

    $form['language'] = [
      '#title' => $this->t('Language'),
      '#type' => 'language_select',
      '#default_value' => $entity->getLanguage(),
      '#languages' => LanguageInterface::STATE_CONFIGURABLE,
      '#required' => TRUE,
    ];

    return parent::form($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    /** @var \Drupal\smartip_language_detection\Entity\SmartIpLanguageMapping $entity */
    $entity = $this->entity;
    $db_entities = SmartIpLanguageMapping::loadMultiple();
    /** @var \Drupal\smartip_language_detection\Entity\SmartIpLanguageMapping $db_entity */
    foreach ($db_entities as $db_entity) {
      if ($db_entity->id() === $entity->id()) {
        continue;
      }

      if ($db_entity->getCountry() === $entity->getCountry()) {
        $form_state->setErrorByName('country', $this->t('The country code %country_code already exists and must be unique.', ['%country_code' => $entity->getCountry()]));
      }
      elseif ($db_entity->getLanguage() === $entity->getLanguage()) {
        $form_state->setErrorByName('language', $this->t('The language code %langcode already exists and must be unique.', ['%langcode' => $entity->getLanguage()]));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\smartip_language_detection\Entity\SmartIpLanguageMapping $entity */
    $entity = $this->entity;
    $status = $entity->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label mapping.', [
          '%label' => $entity->getCountryName(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label mapping.', [
          '%label' => $entity->getCountryName(),
        ]));
    }
    $form_state->setRedirectUrl($entity->toUrl('collection'));
  }

}
