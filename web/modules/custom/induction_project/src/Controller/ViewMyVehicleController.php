<?php

namespace Drupal\induction_project\Controller;

class ViewMyVehicleController {

  public function build($vehicle_nid) {
    $vehicle = \Drupal::entityTypeManager()->getStorage('node')->load($vehicle_nid);
    return [
      '#generic_text' => 'Hello, World!',
      '#vehicle_title' => $vehicle->label(),
      '#author_name' => $vehicle->getOwner()->label(),
      '#theme' => 'view_my_vehicle_template'
    ];
  }

}
