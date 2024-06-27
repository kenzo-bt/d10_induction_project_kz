<?php

namespace Drupal\induction_project\Controller;
use \Drupal\Core\Access\AccessResult;

class ViewMyVehicleController {

  public function build($vehicle) {
    $config = \Drupal::config('induction_project.settings');

    return [
      '#generic_text' => $config->get('my_vehicle_page_access_form_custom_text'),
      '#vehicle_title' => $vehicle->label(),
      '#author_name' => $vehicle->getOwner()->label(),
      '#theme' => 'view_my_vehicle_template'
    ];
  }

  public function access() {
    $current_user = \Drupal::currentUser();
    $config = \Drupal::config('induction_project.settings');
    $allowed_roles = array_keys($config->get('my_vehicle_page_access_form_allowed_roles'));
    foreach ($allowed_roles as $role) {
      if ($current_user->hasRole($role))
      {
        return AccessResult::allowed();
      }
    }
    return AccessResult::forbidden();
  }
}
