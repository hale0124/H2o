<?php

namespace Category\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Category\Entity\Category;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class Navlist extends AbstractHelper {

    public function __invoke(NestedTreeRepository $category, Category $root, $active_id = null, $route = 'zfcadmin/categories-administration', $action = "edit") {
        $view = $this->getView();
        $options = array(
            'decorate' => true,
            'rootOpen' => '<ul class="nav nav-list">',
            'rootClose' => '</ul>',
            'childOpen' => function($node)use($active_id) {
                $class = ($node['id'] == $active_id) ? " active" : "";
                return '<li id="node_' . $node['id'] . '" class="node' . $class . '">';
            },
            'childClose' => '</li>',
            'nodeDecorator' => function($node)use($route,$action,$view){
                $url = $view->url($route, array('action' => $action, 'id' => $node['id']));
                return '<a href="' . $url . '">' . $node['title'] . '</a>';
            }
        );
        $htmlTree = $category->childrenHierarchy($root, false, $options);
        echo $htmlTree;
    }
}