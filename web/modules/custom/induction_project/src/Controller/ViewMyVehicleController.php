<?php

namespace Drupal\induction_project\Controller;
use \Drupal\Core\Access\AccessResult;

class ViewMyVehicleController {

  public function build($vehicle) {
    return [
      '#generic_text' => 'Hello, World!',
      '#vehicle_title' => $vehicle->label(),
      '#author_name' => $vehicle->getOwner()->label(),
      '#theme' => 'view_my_vehicle_template'
    ];
  }

  public function access() {
    $current_user = \Drupal::currentUser();
    if ($current_user->hasRole('vehicle_admin') && !$current_user->hasRole('vehicle_editor')) {
      return AccessResult::allowed();
    }
    return AccessResult::forbidden();
  }
}
