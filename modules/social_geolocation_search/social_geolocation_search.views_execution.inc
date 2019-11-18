<?php

/**
 * @file
 * Views alter hook implementations for the Social Geolocation Search module.
 */

use Drupal\search_api\Plugin\views\query\SearchApiQuery;
use Drupal\views\Plugin\views\query\QueryPluginBase;
use Drupal\views\ViewExecutable;

/**
 * Implements hook_views_query_alter().
 *
 * This is implemented here instead of in hook_search_api_solr_query_alter
 * because that hook runs after the query options that are needed are already
 * processed in SearchApiSolrBackend::search. That makes it impossible to
 * trigger the required call to SearchApiSolrBackend::setSpatial that
 * happens there.
 */
function social_geolocation_search_views_query_alter(ViewExecutable $view, QueryPluginBase $query) {
  // If this is not a SearchApiQuery then there's nothing to do here.
  if (!$query instanceof SearchApiQuery) {
    return;
  }

  $index = $query->getIndex();

  // If this is not a SOLR back-end then there's nothing to do.
  // Altering the database query happens in social_geolocation_search.module.
  if (!$index->hasValidServer() || $index->getServerInstance()->getBackendId() !== 'search_api_solr') {
    return;
  }

  _social_geolocation_search_alter_solr_query($query);
}
