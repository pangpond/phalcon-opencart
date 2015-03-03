<?php

namespace Phoenix\Library\Elements;

use Phalcon\Mvc\User\Component;
/**
 * Elements
 *
 * Helps to build UI elements for the application
 */
class Elements extends Component
{

    private $_headerMenu = array(
        'pull-left' => array(
            'index' => array(
                'caption' => 'Home',
                'action' => 'index'
            ),
            'invoices' => array(
                'caption' => 'Invoices',
                'action' => 'index'
            ),
            'about' => array(
                'caption' => 'About',
                'action' => 'index'
            ),
            'contact' => array(
                'caption' => 'Contact',
                'action' => 'index'
            ),
        ),
        'pull-right' => array(
            'session' => array(
                'caption' => 'Log In/Sign Up',
                'action' => 'index'
            ),
        )
    );

    private $_tabs = array(
        'Invoices' => array(
            'controller' => 'invoices',
            'action' => 'index',
            'any' => false
        ),
        'Companies' => array(
            'controller' => 'companies',
            'action' => 'index',
            'any' => true
        ),
        'Products' => array(
            'controller' => 'products',
            'action' => 'index',
            'any' => true
        ),
        'Product Types' => array(
            'controller' => 'producttypes',
            'action' => 'index',
            'any' => true
        ),
        'Your Profile' => array(
            'controller' => 'invoices',
            'action' => 'profile',
            'any' => false
        )
    );

    private $_class = array(
        'อนุบาล 1' => 'อ1',
        'อนุบาล 2' => 'อ.2',
        'อนุบาล 3' => 'อ.3',
        'ประถมศึกษาปีที่ 1' => 'ป.1',
        'ประถมศึกษาปีที่ 2' => 'ป.2',
        'ประถมศึกษาปีที่ 3' => 'ป.3',
        'ประถมศึกษาปีที่ 4' => 'ป.4',
        'ประถมศึกษาปีที่ 5' => 'ป.5',
        'ประถมศึกษาปีที่ 6' => 'ป.6',
        'มัธยมศึกษาปีที่ 1' => 'ม.1',
        'มัธยมศึกษาปีที่ 2' => 'ม.2',
        'มัธยมศึกษาปีที่ 3' => 'ม.3',
        'มัธยมศึกษาปีที่ 4' => 'ม.4',
        'มัธยมศึกษาปีที่ 5' => 'ม.5',
        'มัธยมศึกษาปีที่ 6' => 'ม.6',
    );

    /**
     * Builds header menu with left and right items
     *
     * @return string
     */
    public function getMenu()
    {

        $auth = $this->session->get('auth');
        if ($auth) {
            $this->_headerMenu['pull-right']['session'] = array(
                'caption' => 'Log Out',
                'action' => 'end'
            );
        } else {
            unset($this->_headerMenu['pull-left']['invoices']);
        }

        echo '<div class="nav-collapse">';
        $controllerName = $this->view->getControllerName();
        foreach ($this->_headerMenu as $position => $menu) {
            echo '<ul class="nav ', $position, '">';
            foreach ($menu as $controller => $option) {
                if ($controllerName == $controller) {
                    echo '<li class="active">';
                } else {
                    echo '<li>';
                }
                echo Phalcon\Tag::linkTo($controller.'/'.$option['action'], $option['caption']);
                echo '</li>';
            }
            echo '</ul>';
        }
        echo '</div>';
    }

    public function getTabs()
    {
        $controllerName = $this->view->getControllerName();
        $actionName = $this->view->getActionName();
        echo '<ul class="nav nav-tabs">';
        foreach ($this->_tabs as $caption => $option) {
            if ($option['controller'] == $controllerName && ($option['action'] == $actionName || $option['any'])) {
                echo '<li class="active">';
            } else {
                echo '<li>';
            }
            echo Phalcon\Tag::linkTo($option['controller'].'/'.$option['action'], $caption), '<li>';
        }
        echo '</ul>';
    }

    public function getClass()
    {
        return $this->_class;
    }
}
