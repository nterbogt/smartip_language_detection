<?php

namespace Drupal\smartip_language_detection;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\smartip_language_detection\Form\SmartIpLanguageDetectionForm;

/**
 * Provides a listing of Smart IP language mapping entities.
 */
class SmartIpLanguageMappingListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['country'] = $this->t('Country');
    $header['language'] = $this->t('Language');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   *
   * @var \Drupal\smartip_language_detection\Entity\SmartIpLanguageMapping $entity
   */
  public function buildRow(EntityInterface $entity) {
    $row['country'] = $entity->getCountryName();
    $row['language'] = $entity->getLanguageName();
    return $row + parent::buildRow($entity);
  }

}
