<?php

namespace Drupal\smartip_language_detection\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Entity\EntityMalformedException;

/**
 * Defines the Smart IP language mapping entity.
 *
 * @ConfigEntityType(
 *   id = "smartip_language_mapping",
 *   label = @Translation("Smart IP language mapping"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\smartip_language_detection\SmartIpLanguageMappingListBuilder",
 *     "form" = {
 *       "add" = "Drupal\smartip_language_detection\Form\SmartIpLanguageMappingForm",
 *       "edit" = "Drupal\smartip_language_detection\Form\SmartIpLanguageMappingForm",
 *       "delete" = "Drupal\smartip_language_detection\Form\SmartIpLanguageMappingDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "smartip_language_mapping",
 *   admin_permission = "administer smartip_language_mapping",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/regional/language/detection/smartip/{smartip_language_mapping}",
 *     "add-form" = "/admin/config/regional/language/detection/smartip/add",
 *     "edit-form" = "/admin/config/regional/language/detection/smartip/{smartip_language_mapping}/edit",
 *     "delete-form" = "/admin/config/regional/language/detection/smartip/{smartip_language_mapping}/delete",
 *     "collection" = "/admin/config/regional/language/detection/smartip"
 *   }
 * )
 */
class SmartIpLanguageMapping extends ConfigEntityBase {

  /**
   * The mapping ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The mapping label.
   *
   * @var string
   */
  protected $label;

  /**
   * The mapping country.
   *
   * @var string
   */
  protected $country;

  /**
   * The mapping language.
   *
   * @var string
   */
  protected $language;

  /**
   * Get the country of the mapping.
   */
  public function getCountry() {
    return $this->country;
  }

  /**
   * Get the user friendly name of country.
   *
   * @return string|null
   *   The human readable country name.
   */
  public function getCountryName() {
    if (!$this->country) {
      return NULL;
    }

    $countries = \Drupal::service('country_manager')->getList();

    return isset($countries[$this->country]) ? $countries[$this->country] : NULL;
  }

  /**
   * Get the language of the mapping.
   */
  public function getLanguage() {
    return $this->language;
  }

  /**
   * Get the user friendly name of language.
   *
   * @return string|null
   *   The human readable language name.
   */
  public function getLanguageName() {
    if (!$this->language) {
      return NULL;
    }

    return \Drupal::languageManager()->getLanguageName($this->language);
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Core\Entity\EntityMalformedException
   *   When attempting to save a Smart IP language mapping with no country.
   */
  public function save() {
    if (empty($this->country)) {
      throw new EntityMalformedException('The Smart IP language mapping does not have a country.');
    }

    // Auto populate machine name and label.
    $this->label = $this->country;
    $this->id = 'smartip_language_mapping_' . strtolower($this->country);

    parent::save();
  }

}
