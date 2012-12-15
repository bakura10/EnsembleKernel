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
 * @package     Ensemble\KernelDoctrineORM
 * @author      Jurian Sluiman <jurian@soflomo.com>
 * @copyright   2012 Soflomo http://soflomo.com.
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://ensemble.github.com
 */

namespace Ensemble\Kernel\Service;

use Ensemble\Kernel\Mapper\PageInterface as PageMapperInterface;
use Ensemble\Kernel\Model\PageCollection as PageCollectionModel;
use Ensemble\Kernel\Model\PageInterface  as PageModel;

/**
 * Description of Page
 */
class Page
{
    /**
     * @var PageMapperInterface
     */
    protected $pageMapper;


    /**
     * Constructor
     *
     * @param PageMapperInterface $pageMapper
     */
    public function __construct(PageMapperInterface $pageMapper)
    {
        $this->pageMapper = $pageMapper;
    }

    /**
     * Create a new page
     *
     * @param  PageModel $page
     * @return PageModel
     */
    public function persist(PageModel $page)
    {
        return $this->pageMapper->persist($page);
    }

    /**
     * Update an existing page
     *
     * @param  PageModel $page
     * @return PageModel
     */
    public function update(PageModel $page)
    {
        return $this->pageMapper->update($page);
    }

    /**
     * Delete an existing page
     *
     * @param  PageModel $page
     * @return bool
     */
    public function delete(PageModel $page)
    {
        return $this->pageMapper->delete($page);
    }

    /**
     * Find page based on id
     *
     * @param  int $id
     * @return Page
     */
    public function find($id)
    {
        return $this->pageMapper->find($id);
    }

    /**
     * Get the tree of pages
     *
     * @return PageCollectionModel
     */
    public function getTree()
    {
        $pages      = $this->pageMapper->getRootNodes('order', 'ASC');
        $collection = new PageCollectionModel($pages);

        return $collection;
    }
}