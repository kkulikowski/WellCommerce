<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\RoutingBundle\Provider;

use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Route as SymfonyRoute;
use Symfony\Component\Routing\RouteCollection;
use WellCommerce\Bundle\RoutingBundle\Entity\RouteInterface;
use WellCommerce\Bundle\RoutingBundle\Generator\RouteGeneratorCollection;
use WellCommerce\Bundle\RoutingBundle\Generator\RouteGeneratorInterface;
use WellCommerce\Bundle\RoutingBundle\Repository\RouteRepositoryInterface;

/**
 * Class RouteProvider
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class RouteProvider implements RouteProviderInterface
{
    const DYNAMIC_PREFIX   = 'dynamic_';
    const CANDIDATES_LIMIT = 1000;

    /**
     * Collection of route generators available in collection
     *
     * @var RouteGeneratorCollection
     */
    protected $generators;

    /**
     * @var RouteRepositoryInterface
     */
    protected $repository;

    /**
     * Constructor
     *
     * @param RouteGeneratorCollection $generators
     * @param RouteRepositoryInterface $repository
     */
    public function __construct(RouteGeneratorCollection $generators, RouteRepositoryInterface $repository)
    {
        $this->generators = $generators;
        $this->repository = $repository;
    }

    /**
     * Returns route collection for current request
     *
     * @param Request $request
     *
     * @return RouteCollection
     */
    public function getRouteCollectionForRequest(Request $request)
    {
        $collection = new RouteCollection();
        $path       = $this->getNormalizedPath($request);
        $resource   = $this->repository->findRouteByPath($path);

        if ($resource) {
            $route = $this->createRoute($resource);
            $collection->add(
                self::DYNAMIC_PREFIX . $resource->getId(),
                $route
            );
        }

        return $collection;
    }

    /**
     * Returns route by its identifier
     *
     * @param string $identifier
     *
     * @return SymfonyRoute
     */
    public function getRouteByName($identifier)
    {
        $id       = str_replace(self::DYNAMIC_PREFIX, '', $identifier);
        $resource = $this->repository->find($id);

        if (!$resource) {
            throw new RouteNotFoundException(sprintf('No route found for id "%s"', $id));
        }

        return $this->createRoute($resource);
    }

    public function getRoutesByNames($names, $parameters = [])
    {
        $collection = $this->repository->findBy([], null, self::CANDIDATES_LIMIT);
        $routes     = [];
        foreach ($collection as $item) {
            $routes[] = $this->getRouteByName($item->getId());
        }

        return $routes;
    }

    /**
     * Returns normalized path used in resource query
     *
     * @param Request $request
     *
     * @return mixed
     */
    private function getNormalizedPath(Request $request)
    {
        $path  = ltrim($request->getPathInfo(), '/');
        $paths = explode(RouteGeneratorInterface::PATH_PARAMS_SEPARATOR, $path);

        return current($paths);
    }

    /**
     * Creates a route
     *
     * @param RouteInterface $resource
     *
     * @return null|SymfonyRoute
     */
    private function createRoute(RouteInterface $resource)
    {
        $route = null;

        /**
         * @var \WellCommerce\Bundle\RoutingBundle\Generator\RouteGeneratorInterface $generator
         */
        foreach ($this->generators as $generator) {
            if ($generator->supports($resource->getType())) {
                $route = $generator->generate($resource);
                break;
            }
        }

        if (null === $route) {
            throw new RouteNotFoundException(sprintf('No possible generator found for route "%s"', $resource->getId()));
        }

        return $route;
    }
}
