<?php

namespace spec\PrometheusApi\Utilities\Uri;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ParserSpec extends ObjectBehavior
{

    function it_does_not_return_the_query_with_entities()
    {
        $idPlaceholder = '{random_id}';

        $uri = '/products/?include=primary_image';
        $this->entities($uri)->shouldReturn(['products']);

        $uri = '/products/{random_id}/?query=test';
        $this->entities($uri, $idPlaceholder)->shouldReturn(['products']);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('App\Uri\Parser');
    }

    function it_parses_an_array_of_ids_from_the_end_entity()
    {
        $uri = '/sites/1/products/3/images/3,2,1';
        $this->ids($uri)->shouldReturn([1, 3, [3, 2, 1]]);
    }

    function it_retrieves_ids_from_the_uri()
    {
        $uri = '/sites/1/products/2/images/3';
        $this->ids($uri)->shouldReturn([1, 2, 3]);
    }

    function it_returns_an_array_of_entities()
    {
        $uri = '/sites/1/products/2/images/3';
        $this->entities($uri)->shouldReturn(['sites', 'products', 'images']);
    }

    function it_returns_an_array_of_entities_without_the_array_of_ids()
    {
        $uri = '/sites/1/products/2/images/3,2,1';
        $this->entities($uri)->shouldReturn(['sites', 'products', 'images']);
    }

    function it_returns_entities_that_do_not_equal_an_id_placeholder()
    {
        $uri = '/sites/{id}/products/{id}/images/{id}';
        $idPlaceholder = '{id}';
        $this->entities($uri, $idPlaceholder)->shouldReturn(['sites', 'products', 'images']);
    }

    function it_returns_entities_that_have_ids()
    {
        $uri = '/sites/1/products/4/images/10,6,7';
        $this->idEntities($uri)->shouldReturn(['sites', 'products', 'images']);
    }

    function it_returns_entities_that_need_ids()
    {
        $uri = '/sites/{random_id}/products/{random_id}/images/{random_id}';
        $idPlaceholder = '{random_id}';
        $this->idEntities($uri, $idPlaceholder)->shouldReturn(['sites', 'products', 'images']);
    }

    function it_returns_the_query()
    {
        $uri = '/products/?include=primary_image';
        $this->getQuery($uri)->shouldReturn('?include=primary_image');
    }

    function it_will_return_all_instances_of_an_id_placeholder()
    {
        $uri = '/sites/{id}/products/{id}/images/{id}';
        $idPlaceholder = '{id}';
        $this->ids($uri, $idPlaceholder)->shouldReturn([$idPlaceholder, $idPlaceholder, $idPlaceholder]);
    }

    function it_will_return_an_empty_array_of_ids_if_there_are_none_in_the_uri()
    {
        $uri = '/sites';
        $this->ids($uri)->shouldReturn([]);
    }
}