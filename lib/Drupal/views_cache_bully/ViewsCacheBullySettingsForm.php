<?php
/**
 * @file
 * Contains \Drupal\views_cache_bully\ViewsCacheBullySettingsForm.
 */

namespace Drupal\views_cache_bully;

use Drupal\system\SystemConfigFormBase;

/**
 * Configure Views Cache Bully settings for this site.
 */
class ViewsCacheBullySettingsForm extends SystemConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'views_cache_bully_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, array &$form_state) {
    $config = $this->configFactory->get('views_cache_bully.settings');

    $options = array(60, 300, 1800, 3600, 21600, 518400);
    $options = drupal_map_assoc($options, 'format_interval');

    $form['views_cache_bully_results_lifespan'] = array(
      '#type' => 'select',
      '#title' => t('Query results'),
      '#description' => t('The length of time raw query results should be cached.'),
      '#options' => $options,
      '#default_value' => $config->get('views_cache_bully_results_lifespan'),
    );
    $form['views_cache_bully_output_lifespan'] = array(
      '#type' => 'select',
      '#title' => t('Rendered output'),
      '#description' => t('The length of time rendered HTML output should be cached.'),
      '#options' => $options,
      '#default_value' => $config->get('views_cache_bully_output_lifespan'),
    );
    $form['views_cache_bully_exempt_exposed'] = array(
      '#type' => 'checkbox',
      '#title' => t('Exempt all views with exposed forms from bullying'),
      '#description' => t('Views using exposed forms will not be cached by Views Cache Bully.'),
      '#default_value' => $config->get('views_cache_bully_exempt_exposed'),
    );

    $views = drupal_map_assoc(array_keys(views_get_all_views()));
    $form['views_cache_bully_exemptions'] = array(
      '#type' => 'checkboxes',
      '#title' => t('Exempt the following views from bullying'),
      '#description' => t('Checked views will not be cached.'),
      '#options' => $views,
      '#default_value' => $config->get('views_cache_bully_exemptions') ? $config->get('views_cache_bully_exemptions') : array(),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, array &$form_state) {

    Drupal::config('views_cache_bully.settings')
      ->set('results_lifespan', $form_state['values']['views_cache_bully_results_lifespan'])
      ->set('output_lifespan', $form_state['values']['views_cache_bully_output_lifespan'])
      ->set('exempt_exposed', $form_state['values']['views_cache_bully_exempt_exposed'])
      ->set('exemptions', $form_state['values']['views_cache_bully_exemptions'])
      ->save();

    parent::submitForm($form, $form_state);
  }

}