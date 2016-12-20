<?php

/**
 * @file
 * Contains \Drupal\session_node_access\Form\SessionNodeAccessSettingsForm.
 */

namespace Drupal\session_node_access\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure session_node_access settings for this site.
 */
class SessionNodeAccessSettingsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'session_node_access_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'session_node_access.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('session_node_access.settings');

    // Fieldsets.
    $form['moduleinfo'] = array(
      '#markup' => t('On this page you can grant additional permissions to users after they create a node.
      Consider this use case: Content created by anonymous users is set to be not published until a mod reviews it.
      With this module the user gets to view/edit/delete their freshly created content without it to be publicly
      accessible.'),
      '#prefix' => '<p>',
      '#suffix' => '</p>',
    );
    $form['node_types_fieldset'] = array(
      '#type' => 'fieldset',
      '#title' => t('Restrict by content type'),
      '#description' => t('Grant per-session node permissions to certain users who create nodes of the following content types.
      If a user creates a node of the checked content type, they will get access to it until their session expires.
      Check at least one element.'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
    );
    $form['user_roles_fieldset'] = array(
      '#type' => 'fieldset',
      '#title' => t('Restrict by user roles'),
      '#description' => t('Grant per-session node permissions to all users assigned to the following roles.
      <strong>Note:</strong> In most cases checking roles other than \'anonymous\' won\'t be necessary because of the available \'View own unpublished\'
      options in the permissions tab. Check at least one element.'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
    );
    $form['operations_fieldset'] = array(
      '#type' => 'fieldset',
      '#title' => t('Grant these permissions'),
      '#description' => t('Said users will be granted the following permissions to nodes they create.
      These permissions will expire along with the user\'s session. Check at least one element.'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
    );

    // Prepare content type settings for display.
    $node_types = node_type_get_names();
    $default_value = array();
    foreach ($node_types as $machine_name => $human_name) {
      if (isset($config->get('node_types')[$machine_name]) && $config->get('node_types')[$machine_name]) {
        $default_value[] = $machine_name;
      }
    }

    // Display content type settings.
    $form['node_types_fieldset']['node_types'] = array(
      '#type' => 'checkboxes',
      '#options' => $node_types,
      '#default_value' => $default_value,
    );

    // Prepare user role settings for display.
    $user_roles = user_roles();
    $default_value = array();
    $options = [];
    foreach ($user_roles as $role_id => $role) {
      if (isset($config->get('user_roles')[$role_id]) && $config->get('user_roles')[$role_id]) {
        $default_value[] = $role_id;
      }
      $options[$role_id] = $role_id;
    }
    // Display user role settings.
    $form['user_roles_fieldset']['user_roles'] = array(
      '#type' => 'checkboxes',
      '#options' => $options,
      '#default_value' => $default_value,
    );

    // Prepare permissions settings for display.
    $operations = array(
      'view' => t('View'),
      'update' => t('Update'),
      'delete' => t('Delete'),
    );
    $default_value = array();
    foreach ($operations as $operation => $human_operation) {
      if (isset($config->get('operations')[$operation]) && $config->get('operations')[$operation]) {
        $default_value[] = $operation;
      }
    }

    // Display operation settings.
    $form['operations_fieldset']['operations'] = array(
      '#type' => 'checkboxes',
      '#options' => $operations,
      '#default_value' => $default_value,
    );

    // Prepare publishing settings for display.
    $published = $config->get('published');

    // Display publishing settings.
    $form['published_fieldset']['published'] = array(
      '#title' => t('Take effect only on published nodes'),
      '#description' => t('Usually this is unchecked, as most use cases for this module require it to give temporary
      permissions to users to nodes they create but which are still unpublished by the moderator.'),
      '#type' => 'checkbox',
      '#default_value' => $published,
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = \Drupal::service('config.factory')
      ->getEditable('session_node_access.settings');

    $node_types = $form_state->getValue('node_types');
    $config->set('node_types', $node_types)->save();

    $user_roles = $form_state->getValue('user_roles');
    $config->set('user_roles', $user_roles)->save();

    $operations = $form_state->getValue('operations');
    $config->set('operations', $operations)->save();

    $config->set('published', $form_state->getValue('published'))->save();

    parent::submitForm($form, $form_state);
  }
}
