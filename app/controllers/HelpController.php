<?php
namespace Phoenix\Controllers;

use Phalcon\Tag as Tag;
use Phoenix\Models\Users;


class HelpController extends ControllerBase
{
    public function initialize()
    {
        Tag::setTitle('Help');
        $this->loadCustomTrans('help');
        parent::initialize();

        $this->crumbs->add('help', $this->applicationConfig->baseUri.'help', 'Help');


    }

    public function indexAction()
    {
        $this->crumbs->add('help', '', 'Help', false);

    }

    public function howtoAction()
    {

        if(empty($this->crumbs->crumbs['howto']['label']))
            $this->crumbs->add('howto', '/', 'How to', false); 
    }

    public function missionAction()
    {
        if(empty($this->crumbs->crumbs['mission']['label']))
            $this->crumbs->add('mission', '/', 'Mission', false); 

    }

    public function downloadAction()
    {
        if(empty($this->crumbs->crumbs['download']['label']))
            $this->crumbs->add('download', '/', 'Download', false); 

    }

}