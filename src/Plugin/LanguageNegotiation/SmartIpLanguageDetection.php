<?php

namespace Drupal\smartip_language_detection\Plugin\LanguageNegotiation;

use Drupal\language\LanguageNegotiationMethodBase;
use Drupal\smart_ip\SmartIp;
use Drupal\smartip_language_detection\Entity\SmartIpLanguageMapping;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class for identifying language from the IP address.
 *
 * @LanguageNegotiation(
 *   id = Drupal\smartip_language_detection\Plugin\LanguageNegotiation\SmartIpLanguageDetection::METHOD_ID,
 *   weight = -1,
 *   name = @Translation("IP address"),
 *   description = @Translation("Uses Smart IP to get language based on visitor's IP address."),
 *   config_route_name = "smartip.language_detection"
 * )
 */
class SmartIpLanguageDetection extends LanguageNegotiationMethodBase implements ContainerFactoryPluginInterface {

  /**
   * The language negotiation method id.
   */
  const METHOD_ID = 'smartip-language-detection';

  /**
   * The state service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * Constructs a new SmartIpLanguageDetection instance.
   *
   * @param \Drupal\Core\State\StateInterface $state
   *   The state service.
   */
  public function __construct(StateInterface $state) {
    $this->state = $state;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('state')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getLangcode(Request $request = NULL) {
    $langcode = NULL;

    if (!$request || !$this->languageManager) {
      return $langcode;
    }

    \Drupal::service('page_cache_kill_switch')->trigger();

    // We can't use the service here because it uses sessions, and they hurt us.
    $ip = \Drupal::request()->getClientIp();
    $location = SmartIp::query($ip);

    // Defensive checking for country code.
    if (!isset($location['countryCode'])) {
      return $langcode;
    }

    $country_code = $location['countryCode'];
    $mapping_ids = \Drupal::entityQuery('smartip_language_mapping')
      ->condition('country', $country_code)
      ->execute();

    // No matching country-language is found.
    if (empty($mapping_ids)) {
      return $langcode;
    }

    // Update langcode based on mapping values.
    $mapping_id = reset($mapping_ids);
    $mapping = SmartIpLanguageMapping::load($mapping_id);
    return $mapping->getLanguage();
  }

}
