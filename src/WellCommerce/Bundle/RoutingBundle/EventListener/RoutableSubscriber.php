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

namespace WellCommerce\Bundle\RoutingBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use WellCommerce\Bundle\RoutingBundle\Entity\RoutableSubjectInterface;

/**
 * Class RoutableSubscriber
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class RoutableSubscriber implements EventSubscriber
{
    /**
     * Add Route for new entity
     *
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof RoutableSubjectInterface) {
            $route = $this->addRoute($entity);
            $entity->setRoute($route);
        }
    }

    /**
     * Adds new route
     *
     * @param RoutableSubjectInterface $entity
     *
     * @return \WellCommerce\Bundle\RoutingBundle\Entity\RouteInterface
     */
    protected function addRoute(RoutableSubjectInterface $entity)
    {
        $route = $entity->getRouteEntity();
        $route->setPath($entity->getSlug());
        $route->setLocale($entity->getLocale());
        $route->setIdentifier($entity->getTranslatable());

        return $route;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
        ];
    }
}
