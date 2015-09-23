<?php
namespace PrometheusApi\Utilities\Contracts\Uri;

interface Parser
{
    /**
     * Returns the entities from a URI
     *
     * /sites/1/products would return ['sites', 'products']
     *
     * @param string      $uri
     * @param string|null $idPlaceholder
     *
     * @return array
     */
    public function entities($uri, $idPlaceholder = null);

    /**
     * Returns the query string from the URI
     *
     * @param string $uri
     *
     * @return string
     */
    public function getQuery($uri);

    /**
     * @param string $uri
     *
     * @return string
     */
    public function getResource($uri);

    /**
     * Returns the entities that have ids associated with them in the URI
     *
     * /sites/1/products would return ['sites']
     *
     * @param string      $uri
     * @param null|string $idPlaceholder
     *
     * @return array
     */
    public function idEntities($uri, $idPlaceholder = null);

    /**
     * This will get either the numeric ids or the instances
     * of a match of an id array string
     *
     * For example:
     *
     * /sites/1/products/1,2,4 would return [1, '1,2,4']
     *
     * @param string      $uri
     * @param string|null $idPlaceholder
     *
     * @return array
     */
    public function ids($uri, $idPlaceholder = null);

    /**
     * returns a count of the ids needed based on the placeholder
     *
     * @param string $uri
     * @param string $placeholder
     *
     * @return array
     */
    public function idsNeededCount($uri, $placeholder);
}
