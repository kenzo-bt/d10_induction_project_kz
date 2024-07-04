<?php

namespace Drupal\vehicles_custom\Drush\Commands;

use Drush\Attributes as CLI;
use Drush\Commands\DrushCommands;

/**
 * Vehicles Drush commands.
 */
final class VehiclesCommands extends DrushCommands {
  /**
   * Load vehicle data csv into database
   */
  #[CLI\Command(name: 'vehicles_custom:load-data', aliases: ['load-vehicle-data'])]
  #[CLI\Usage(name: 'vehicles_custom:load-data', description: 'Load vehicle data csv into database')]
  public function loadData() {
    // Truncate the database before inserting records from csv
    $database = \Drupal::database();
    $database->truncate('vehicles_custom')->execute();
    // Load csv and insert records into vehicles_custom table
    $csv = file('../vehicle_data.csv');
    array_shift($csv);
    foreach ($csv as $line) {
      // Get fields from each individual record
      $fields = str_getcsv($line);
      $custom_id = $fields[0];
      $vehicle_name = $fields[1];
      $vehicle_colour = $fields[2];
      $owner_id = $fields[3];
      // Insert into database table
      $database->insert('vehicles_custom')
        ->fields([
          'custom_id' => $custom_id,
          'vehicle_name' => $vehicle_name,
          'vehicle_colour' => $vehicle_colour,
          'owner_id' => $owner_id,
        ])
        ->execute();
    }
  }
}