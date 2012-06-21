<?php

/*
 * This is free and unencumbered software released into the public domain.
 * 
 * Anyone is free to copy, modify, publish, use, compile, sell, or
 * distribute this software, either in source code form or as a compiled
 * binary, for any purpose, commercial or non-commercial, and by any
 * means.
 * 
 * In jurisdictions that recognize copyright laws, the author or authors
 * of this software dedicate any and all copyright interest in the
 * software to the public domain. We make this dedication for the benefit
 * of the public at large and to the detriment of our heirs and
 * successors. We intend this dedication to be an overt act of
 * relinquishment in perpetuity of all present and future rights to this
 * software under copyright law.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 * 
 * For more information, please refer to <http://unlicense.org/>
 * 
 * @package
 * @copyright  Copyright (c) 2009-2012 Soflomo (http://soflomo.com)
 * @license    http://unlicense.org Unlicense
 */

namespace SlmCmfKernel\Listener;

use Zend\EventManager\EventManagerInterface as EventManager;
use Zend\EventManager\Event;
use Zend\Mvc\MvcEvent;

use SlmCmfKernel\Model\PageInterface as Page;
use SlmCmfKernel\Service\PageInterface as PageService;
use SlmCmfKernel\Exception;

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
            'SlmCmfKernel',
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