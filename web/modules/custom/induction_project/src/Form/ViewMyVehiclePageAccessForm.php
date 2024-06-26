<?php
/**
 * @file
 * Contains Drupal\induction_project\Form\ViewMyVehiclePageAccessForm.
 */
namespace Drupal\induction_project\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\Role;

class ViewMyVehiclePageAccessForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'induction_project.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'view_my_vehicle_page_access_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('induction_project.settings');

    $form['my_vehicle_page_access_form_custom_text'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Some custom text'),
      '#description' => $this->t('Text area for entering any content'),
      '#default_value' => $config->get('my_vehicle_page_access_form_custom_text')
    ];

    // Load all user roles to be displayed as checkboxes
    $roles_array = array_map(fn($role): string => $role->label(), Role::loadMultiple());
    $roles_initialized_to_zero = array_map(fn($role): int => 0, $roles_array);

    $form['my_vehicle_page_access_form_allowed_roles'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('What roles can see this page?'),
      '#description' => $this->t('If the role is unchecked, it wont be able to see the page.'),
      '#options' => $roles_array,
      '#default_value' => $config->get('my_vehicle_page_access_form_allowed_roles') ?? $roles_initialized_to_zero
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('induction_project.settings');
    $config
      ->set('my_vehicle_page_access_form_custom_text', $form_state->getValue('my_vehicle_page_access_form_custom_text'))
      ->set('my_vehicle_page_access_form_allowed_roles', $form_state->getValue('my_vehicle_page_access_form_allowed_roles'))
      ->save();
    parent::submitForm($form, $form_state);
  }
}