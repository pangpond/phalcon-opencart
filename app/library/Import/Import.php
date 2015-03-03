<?php

namespace Phoenix\Library\Import;

use Phoenix\Models\Members;
use Phoenix\Models\Membersmeta;

use Phoenix\Models\Items;
use Phoenix\Models\Itemsmeta;
use Phoenix\Models\Itemscopy;
use Phalcon\Mvc\User\Component;


/**
 * DateTime
 *
 * Helps to build UI elements for the application
 */
class Import extends Component
{
    private $defaultStatus = 1;
    public $arrayMemberMetaKey = array(
        'image', 
        'email', 
        'birthdate', 
        'address ', 
        'district', 
        'telephone', 
        'citizen_id', 
        'gender', 
        'province', 
        'zipcode', 
        'expire', 
        'note', 
        'section_class', 
        'section_room', 
        'section_position'
    );


    public $arrayItemMetaKey = array(
        'language',
        'page',
        'cost',
        'print_year',
        'print_month',
        'print_count',
        'group_number',
        'volume',
        'issued',
        'town',
        'abstract',
        'vendor',
        'sponsor',
        'tag'
    );

    public function importMembers ($memberData){
        $memberMetaData = array();
        
        if( $memberId = $this->createMember($memberData) ){

            foreach ($this->arrayMemberMetaKey as $memberMetaKey) {
                if(!empty($memberData[$memberMetaKey]))
                    $memberMetaData[$memberMetaKey] = $memberData[$memberMetaKey];
            }
            if( $this->createMemberMeta($memberId, $memberMetaData) )
                return true;

            return true;
        }
    }

    public function importItems ($itemData){
        $itemMetaData = array();
        
        if( $itemId = $this->createItem($itemData) ){

            foreach ($this->arrayItemMetaKey as $itemMetaKey) {
                if(!empty($itemData[$itemMetaKey]))
                    $itemMetaData[$itemMetaKey] = $itemData[$itemMetaKey];
            }
            if( $this->createItemMeta($itemId, $itemMetaData) )
                $this->createItemCopy($itemId, $itemData);

            return true;
        }
    }

    public function createMember($memberData)
    {
        $member = new Members();



        $member->username = $memberData['username'];
        $member->password = sha1($memberData['password']);
        $member->display_name = $memberData['display_name'];
        $member->member_type = $memberData['member_type'];
        $member->member_code = $memberData['member_code'];
        $member->title = $this->elements->searchPrefixName($memberData['title']);
        $member->firstname = $memberData['firstname'];
        $member->lastname = $memberData['lastname'];
        $member->status = $memberData['status'];
        $member->registered = $memberData['registered'];

        if (!$member->save()) {
            foreach ($member->getMessages() as $message) {
                $this->flash->error($message);
            }
        }

        return $member->member_id;
    }

    public function createMemberMeta($memberId, $memberMetaData)
    {
        $metaArray = $memberMetaData;

        if(!empty($metaArray['section_class']) || !empty($metaArray['section_room'])  || !empty($metaArray['section_position'])){

            $metaArray['section_class'] = empty($metaArray['section_class']) ? '' : $metaArray['section_class'];
            $metaArray['section_room'] = empty($metaArray['section_room']) ? '' : $metaArray['section_room'];
            $metaArray['section_position'] = empty($metaArray['section_position']) ? '' : $metaArray['section_position'];

            $metaSectionArray = array(
                                'class' => $metaArray['section_class'], 
                                'room' => $metaArray['section_room'],
                                'position' => $metaArray['section_position']);

            $metaArray['section'] = json_encode($metaSectionArray);
            $metaArray['section_position'] = $metaArray['section_class'] = $metaArray['section_room'] = '';
        }

        foreach ($metaArray as $metaKey => $metaValue) {
            if(!empty($metaValue)){
                $memberMeta = new Membersmeta();
                $memberMeta->member_id = $memberId;
                $memberMeta->meta_key = $metaKey;
                $memberMeta->meta_value = $metaValue;

                if (!$memberMeta->save()) {
                    foreach ($memberMeta->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                        return $this->dispatcher->forward(array(
                        "controller" => "members",
                        "action" => "new"
                    ));
                }
            }
        }
    }

    public function createItem($itemData)
    {
        $item = new Items();

        $item->author = $itemData['author'];
        $item->name = $itemData['name'];
        $item->publish = $itemData['publish'];
        $item->publisher = $itemData['publisher'];
        $item->identity = $itemData['identity'];
        $item->status = $itemData['status'];
        $item->type_id = $itemData['type_id'];
        $item->cat_id = $itemData['cat_id'];
        $item->subcat_id = $itemData['subcat_id'];
        $item->group_id = $itemData['group_id'];
        $item->author_code = $itemData['author_code'];
        $item->registered = $itemData['registered'];

        if (!$item->save()) {
            foreach ($item->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "items",
                "action" => "new"
            ));
        }

        return $item->item_id;

    }

    public function createItemMeta($itemId, $itemMetaData)
    {
        $metaArray = $itemMetaData;

        if(!empty($metaArray['print_month']) || !empty($metaArray['print_year'])  || !empty($metaArray['print_count'])){

            $metaArray['print_month'] = empty($metaArray['print_month']) ? '' : $metaArray['print_month'];
            $metaArray['print_year'] = empty($metaArray['print_year']) ? '' : $metaArray['print_year'];
            $metaArray['print_count'] = empty($metaArray['print_count']) ? '' : $metaArray['print_count'];

            $metaPrintArray = array(
                                'month' => $metaArray['print_month'], 
                                'year' => $metaArray['print_year'],
                                'count' => $metaArray['print_count']);

            $metaArray['print'] = json_encode($metaPrintArray);
            $metaArray['print_month'] = $metaArray['print_year'] = $metaArray['print_count'] = '';
        }

        foreach ($metaArray as $metaKey => $metaValue) {
            if(!empty($metaValue)){
                $itemMeta = new Itemsmeta();
                $itemMeta->item_id = $itemId;
                $itemMeta->meta_key = $metaKey;
                $itemMeta->meta_value = $metaValue;

                if (!$itemMeta->save()) {
                    foreach ($itemMeta->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                        return $this->dispatcher->forward(array(
                        "controller" => "items",
                        "action" => "new"
                    ));
                }
            }
        }

        return true;

    }

    public function createItemCopy($itemId, $itemData)
    {
        $itemData['copy_offset'] = (empty($itemData['copy_offset'])) ? 1 : $itemData['copy_offset'];

        for ($copy = $itemData['copy_offset']; $copy <= $itemData['copy']; $copy++) { 
            $itemCopy = new ItemsCopy();

            $itemCopy->item_id = $itemId;
            $itemCopy->copy = $copy;
            $itemCopy->status = $this->defaultStatus;
            $itemCopy->registered = $itemData['registered'];

            if (!$itemCopy->save()) {
                foreach ($item->getMessages() as $message) {
                    $this->flash->error($message);
                }

                return $this->dispatcher->forward(array(
                    "controller" => "items",
                    "action" => "new"
                ));
            }
        }
        

        return true;

    }


// TRUNCATE `items`;
// TRUNCATE `itemsmeta`;
// TRUNCATE `items_copy`;
}
