<?php
/**
 * Copyright (c) 2012 Soflomo http://soflomo.com.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of the
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package     Ensemble\Kernel
 * @author      Jurian Sluiman <jurian@soflomo.com>
 * @copyright   2012 Soflomo http://soflomo.com.
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://ensemble.github.com
 */

namespace Ensemble\Kernel\Listener;

use Zend\EventManager\EventManagerInterface as EventManager;
use Zend\EventManager\Event;
use Zend\Mvc\MvcEvent;

use Ensemble\Kernel\Model\PageInterface as Page;
use Ensemble\Kernel\Service\PageInterface as PageService;
use Ensemble\Kernel\Exception;

/**
 * Description of ParsePages
 */
class LoadPage
{
    /**
     * @var EventManager
     */
    protected $events;

    /**
     * @var PageService
     */
    protected $pageService;

    public function setEventManager(EventManager $eventManager)
    {
        $eventManager->setIdentifiers(array(
            __CLASS__,
            get_called_class(),
            'Ensemble\Kernel',
        ));
        $this->events = $eventManager;
    }

    public function setPageService(PageService $service)
    {
        $this->pageService = $service;
    }

    public function __invoke(MvcEvent $e)
    {
        $this->loadPage($e);
    }

    public function loadPage(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();
        $pageId     = $routeMatch->getParam('page-id', null);

        if (null === $pageId) {
            return;
        }

        $page = $this->pageService->find($pageId);
        if (!$page instanceof Page) {
            throw new Exception\PageNotFoundException(sprintf(
                'The page could not be found with id %s',
                $pageId
            ));
        }

        $event = new Event(__FUNCTION__, $this, array('page' => $page));
        $this->events->trigger($event);

        $e->setParam('page', $page);
    }
}