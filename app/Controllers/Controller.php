<?php

namespace App\Controllers;

use Interop\Container\ContainerInterface;

/**
 * Class Controller
 *
 * Base controller. Extend all other controllers off of this.
 *
 * @package App\Controllers
 */
class Controller
{
    /**
     * Container instance.
     *
     * @var \Interop\Container\ContainerInterface
     */
    protected $container;

    /**
     * Controller constructor.
     *
     * Allow other controllers to access the container.
     *
     * @param \Interop\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}