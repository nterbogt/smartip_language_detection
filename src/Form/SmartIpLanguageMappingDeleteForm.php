<?php

namespace Drupal\smartip_language_detection\Form;

use Drupal\Core\Entity\EntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Builds the form to delete Smart IP language mapping entities.
 */
class SmartIpLanguageMappingDeleteForm extends EntityConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete %label mapping?', ['%label' => $this->entity->getCountryName()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.smartip_language_mapping.collection');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /** @var \Drupal\smartip_language_detection\Entity\SmartIpLanguageMapping $entity */
    $entity = $this->entity;
    $entity->delete();

    drupal_set_message(
      $this->t('Deleted the %label mapping.', ['%label' => $entity->getCountryName()])
    );

    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
