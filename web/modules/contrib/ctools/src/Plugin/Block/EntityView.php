<?php

namespace Drupal\ctools\Plugin\Block;

use Drupal\Component\Plugin\Exception\ContextException;
use Drupal\Core\Access\AccessResultForbidden;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Plugin\ContextAwarePluginInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block to view a specific entity.
 *
 * @Block(
 *   id = "entity_view",
 *   deriver = "Drupal\ctools\Plugin\Deriver\EntityViewDeriver",
 * )
 */
class EntityView extends BlockBase implements ContextAwarePluginInterface, ContainerFactoryPluginInterface {

  static protected $recusion = [];

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The entity display repository.
   *
   * @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface
   */
  protected $entityDisplayRepository;

  /**
   * Constructs a new EntityView.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entity_display_repository
   *   The entity display repository.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, EntityDisplayRepositoryInterface $entity_display_repository) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->entityTypeManager = $entity_type_manager;
    $this->entityDisplayRepository = $entity_display_repository;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('entity_display.repository')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'view_mode' => 'default',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['view_mode'] = [
      '#type' => 'select',
      '#options' => $this->entityDisplayRepository->getViewModeOptions($this->getDerivativeId()),
      '#title' => $this->t('View mode'),
      '#default_value' => $this->configuration['view_mode'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['view_mode'] = $form_state->getValue('view_mode');
  }

  protected function accessRecursion(EntityInterface $entity, array $config) {
    if (!isset(self::$recusion[$entity->uuid()][$config['view_mode']])) {
      self::$recusion[$entity->uuid()][$config['view_mode']] = 0;
    }
    return self::$recusion[$entity->uuid()][$config['view_mode']]++;
  }

  protected function getAccessRecursion(EntityInterface $entity, array $config) {
    return self::$recusion[$entity->uuid()][$config['view_mode']] ?? 0;
  }

  /**
   * {@inheritdoc}
   */
  public function access(AccountInterface $account, $return_as_object = FALSE) {
    // Check the parent's access.
    $parent_access = parent::access($account, TRUE);
    if (!$parent_access->isAllowed()) {
      return $return_as_object ? $parent_access : $parent_access->isAllowed();
    }

    /** @var \Drupal\Core\Entity\EntityInterface $entity */
    $entity = $this->getContextValue('entity');
    if ($entity) {
      if ($this->getAccessRecursion($entity, $this->getConfiguration())) {
        return $return_as_object ? new AccessResultForbidden() : FALSE;
      }
      return $entity->access('view', $account, $return_as_object);
    }
    return new AccessResultForbidden("No Entity Found.");
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    /** @var $entity \Drupal\Core\Entity\EntityInterface */
    $entity = $this->getContextValue('entity');
    $build = [];
    if ($this::accessRecursion($entity, $this->getConfiguration())) {
      return $build;
    }

    $view_builder = $this->entityTypeManager->getViewBuilder($entity->getEntityTypeId());
    $build = $view_builder->view($entity, $this->configuration['view_mode']);

    CacheableMetadata::createFromObject($entity)
      ->merge(CacheableMetadata::createFromRenderArray($build))
      ->applyTo($build);

    return $build;
  }

}
