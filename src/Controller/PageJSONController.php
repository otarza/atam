<?php

namespace Drupal\atam\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PageJSONController.
 */
class PageJSONController extends ControllerBase
{

    /**
     * Drupal\Core\Entity\EntityTypeManagerInterface definition.
     *
     * @var \Drupal\Core\Entity\EntityTypeManagerInterface
     */
    protected $entityTypeManager;

    /**
     * Constructs a new PageJSONController object.
     * @param EntityTypeManagerInterface $entity_type_manager
     */
    public function __construct(EntityTypeManagerInterface $entity_type_manager)
    {
        $this->entityTypeManager = $entity_type_manager;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('entity_type.manager')
        );
    }

    /**
     * Provide JSON representation of the node.
     *
     * @param $site_api_key
     * @param $nid
     * @return JsonResponse|Response
     * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
     */
    public function getPageJSON($site_api_key, $nid)
    {
        // Prepare response for when $site_api_key and $nid do not match.
        $response = new Response();
        $response->setStatusCode(403);

        // Get site config.
        $siteConfig = $this->config('system.site');

        // Check if $site_api_key matches the value in site config.
        if ($siteConfig->get('siteapikey') == $site_api_key) {
            $node_storage = $this->entityTypeManager->getStorage('node');
            $query = $node_storage->getQuery();

            // Query for page with matching $nid.
            $result_nids = $query->condition('type', 'page')
                ->condition('nid', $nid)
                ->execute();

            // Check the result if there is a node with $nid.
            if (!empty($result_nids)) {
                $page_nid = array_values($result_nids)[0];
                $node = $node_storage->load($page_nid);

                // Prepare JsonResponse.
                $response = new JsonResponse($node->toArray());
            }
        }

        return $response;
    }
}