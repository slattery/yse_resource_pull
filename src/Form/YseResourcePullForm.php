<?php

/**
 * @file
 * Simple config form.
 */

namespace Drupal\yse_resource_pull\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\Display\EntityFormDisplayInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class YseResourcePullForm extends ConfigFormBase {

  /**
   * Constructs an YseResourcePullForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    parent::__construct($config_factory);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'yse_resource_pull';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['yse_resource_pull.settings'];
  }

  /**
   * {@inheritdoc}
   *  Should show a note here and link about role mapping over at CAS Attributes for bulk form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $resource_host = $this->configFactory->get('yse_resource_pull.settings')->get('resource_host');
    $resource_apikey = $this->configFactory->get('yse_resource_pull.settings')->get('resource_apikey');

    $form['resource_host'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Resource URL for lookups'),
      '#default_value' => $resource_host,
      '#min' => 1,
      '#required' => TRUE,
      '#description' => $this->t('The resources server hostname.  Not the full URL, no protocol or paths.  ex: allrecords.university.edu'),
    ];

    $form['resource_apikey'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Key'),
      '#default_value' => $resource_apikey,
      '#min' => 1,
      '#required' => FALSE,
      '#description' => $this->t('String that grants access.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->configFactory->getEditable('yse_resource_pull.settings');
    $config->set('resource_host', $form_state->getValue('resource_host'))->save();
    $config->set('resource_apikey', $form_state->getValue('resource_apikey'))->save();
    //\Drupal::logger('yse_cas_event_subscribers')->notice('Are we being called @yeah?', ['@yeah' => $yeah]);
    parent::submitForm($form, $form_state);
  }


}