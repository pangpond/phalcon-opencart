<?php
namespace Phoenix\Controllers;

use Phalcon\Tag as Tag;
use Phoenix\Models\Members;
use Phoenix\Models\MembersCode;


class ScanController extends ControllerBase
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
        $this->view->disable();
        $this->crumbs->add('help', '', 'Help', false);
        $this->getMembersCode();
    }

    public function getMembersCode() {

        $this->view->disable();

        $queryMembers = "SELECT ma.code, m.member_id, m.firstname, m.lastname FROM Phoenix\Models\Members m LEFT JOIN Phoenix\Models\MembersCode ma ON m.lastname = ma.lastname AND ma.firstname = m.firstname where ma.code is not null AND m.code = '' ORDER BY m.member_id";

        $members = $this->modelsManager->executeQuery($queryMembers);

        foreach ($members as $member) {
            $this->updateMemberCode($member->member_id, $member->code, $member->firstname, $member->lastname);
        }

        if (count($members) == 0)
            return false;
        else
            return $members;
    }

    public function updateMemberCode($memberId, $code, $firstname, $lastname) {

        

        $member = Members::findFirstBymember_id($memberId);
        $member->code = $code;

        if (!$member->save()) {
            foreach ($member->getMessages() as $message) {
                $this->flash->error($message);
            }
        }

        echo "$memberId, $code, $firstname, $lastname \n <br>";
    }

}