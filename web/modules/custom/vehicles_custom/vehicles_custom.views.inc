<?php

/**
 * Implements hook_views_data().
 */
function vehicles_custom_views_data() {
  $data['vehicles_custom'] = [
    'table' => [
      'group' => t('Vehicles custom'),
      'provider' => 'vehicles_custom',
      'base' => [
        'field' => 'custom_id',
        'title' => t('Vehicles custom data'),
        'help' => t('Table with custom vehicles data'),
      ],
    ],
    'custom_id' => [
      'title' => t('Custom vehicle ID'),
      'field' => [
        'id' => 'numeric',
      ],
      'sort' => [
        'id' => 'numeric',
      ],
      'filter' => [
        'id' => 'numeric',
      ],
      'argument' => [
        'id' => 'numeric',
      ],
    ],
    'vehicle_name' => [
      'title' => t('Vehicle name'),
      'field' => [
        'id' => 'standard',
      ],
      'sort' => [
        'id' => 'standard',
      ],
      'filter' => [
        'id' => 'string',
      ],
      'argument' => [
        'id' => 'string',
      ],
    ],
    'vehicle_colour' => [
      'title' => t('Vehicle colour'),
      'field' => [
        'id' => 'standard',
      ],
      'sort' => [
        'id' => 'standard',
      ],
      'filter' => [
        'id' => 'string',
      ],
      'argument' => [
        'id' => 'string',
      ],
    ],
    'owner_id' => [
      'title' => t('Owner ID'),
      'relationship' => [
        'base' => 'users_field_data',
        'base field' => 'uid',
        'id' => 'standard',
        'label' => t('User who owns the vehicle'),
      ],
    ],
  ];

  return $data;
}