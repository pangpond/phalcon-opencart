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
    private $maxRoomNumber = 20;
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
        'อนุบาล 1' => 'อนุบาล 1',
        'อนุบาล 2' => 'อนุบาล 2',
        'อนุบาล 3' => 'อนุบาล 3',
        'ประถมศึกษาปีที่ 1' => 'ประถมศึกษาปีที่ 1',
        'ประถมศึกษาปีที่ 2' => 'ประถมศึกษาปีที่ 2',
        'ประถมศึกษาปีที่ 3' => 'ประถมศึกษาปีที่ 3',
        'ประถมศึกษาปีที่ 4' => 'ประถมศึกษาปีที่ 4',
        'ประถมศึกษาปีที่ 5' => 'ประถมศึกษาปีที่ 5',
        'ประถมศึกษาปีที่ 6' => 'ประถมศึกษาปีที่ 6',
        'มัธยมศึกษาปีที่ 1' => 'มัธยมศึกษาปีที่ 1',
        'มัธยมศึกษาปีที่ 2' => 'มัธยมศึกษาปีที่ 2',
        'มัธยมศึกษาปีที่ 3' => 'มัธยมศึกษาปีที่ 3',
        'มัธยมศึกษาปีที่ 4' => 'มัธยมศึกษาปีที่ 4',
        'มัธยมศึกษาปีที่ 5' => 'มัธยมศึกษาปีที่ 5',
        'มัธยมศึกษาปีที่ 6' => 'มัธยมศึกษาปีที่ 6',
    );

    private $_peopleType = array(
        '1' => 'สามัญ (ผู้อำนวยการ)',
        '2' => 'วิสามัญ (รองผู้อำนวยการ)',
        '3' => 'สมทบ (ครู/อาจารย์)',
        '4' => 'สถาบัน',
    );

    private $_prefixName = array(
        '1' => 'นาย',
        '2' => 'นาง',
        '3' => 'น.ส.',
        '4' => 'ดร.',
        '5' => 'ผศ.',
        '6' => 'รศ.',
        '7' => 'ศ.',
        '8' => 'ผศ. ดร.',
        '9' => 'รศ. ดร.',
        '10' => 'ศ. ดร.',
        '11' => 'ว่าที่ ร.ต.',
        '12' => 'ว่าที่ ร.ต.หญิง',
        '13' => 'ว่าที่ พ.ต.',
        '14' => 'ว่าที่ พ.ต.หญิง',
        '15' => 'ม.ร.ว.',
        '16' => 'ม.ล.',
        '17' => 'สิบเอก',
    );

    private $_prefixNameEn = array(
        '1' => 'Mr.',
        '2' => 'Mrs.',
        '3' => 'Miss',
        '4' => 'Dr.',
        '5' => 'Asst. Prof.',
        '6' => 'Assoc. Prof.',
        '7' => 'Prof.',
        '8' => 'Asst. Prof. Dr.',
        '9' => 'Assoc. Prof. Dr.',
        '10' => 'Prof. Dr.',
        '11' => 'Acting Sub Lt.',
        '12' => 'Acting Sub Lt. (Women)',
        '13' => 'Acting Maj.',
        '14' => 'Acting Maj. (Women)',
        '15' => 'M.R.',
        '16' => 'M.L.',
        '17' => 'Sgt.',
    );

    private $_prefixName3 = array(
        array( 'value' => '1', 'label' => 'เด็กชาย'),
        array( 'value' => '2', 'label' => 'เด็กหญิง'),
        array( 'value' => '3', 'label' => 'นาย'),
        array( 'value' => '4', 'label' => 'นางสาว'),
        array( 'value' => '5', 'label' => 'นาง'),
    );

    private $_prefixNamez = array(
        '1' => 'เด็กชาย',
        '2' => 'เด็กหญิง',
        '3' => 'นาย',
        '4' => 'นางสาว',
        '5' => 'นาง',
    );

    private $_itemLanguage = array(
        '1' => 'ภาษาไทย',
        '2' => 'ภาษาอังกฤษ',
    );

    private $_month = array(
        '1' => 'January',
        '2' => 'February',
        '3' => 'March',
        '4' => 'April',
        '5' => 'May',
        '6' => 'June',
        '7' => 'July',
        '8' => 'August',
        '9' => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December'
    );

    private $_shortMonth = array(
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec"
    );

    private $_status = array(
        '0' => 'In Active',
        '1' => 'Active',
    );

    private $_borrow_fine_status = array(
        'Fine' => "Fine", 'Not Fine' => 'Not Fine'
    );

    private $_bloodGroup = array(
        'A' => 'A',
        'B' => 'B',
        'O' => 'O',
        'AB' => 'AB',
    );

    private $_academicStanding = array(
        '1' => 'ผู้อํานวยการสํานักงานเขตพื้นที่การศึกษาเชี่ยวชาญพิเศษ',
        '2' => 'ผู้อํานวยการสํานักงานเขตพื้นที่การศึกษาเชี่ยวชาญ',
        '3' => 'รองผู้อํานวยการสํานักงานเขตพื้นที่การศึกษาเชี่ยวชาญ',
        '4' => 'รองผู้อํานวยการสํานักงานเขตพื้นที่การศึกษาชํานาญการพิเศษ',
        '5' => 'ผู้อํานวยการเชี่ยวชาญพิเศษ',
        '6' => 'ผู้อํานวยการเชี่ยวชาญ',
        '7' => 'ผู้อํานวยการชํานาญการพิเศษ',
        '8' => 'ผู้อํานวยการชํานาญการ',
        '9' => 'รองผู้อํานวยการเชี่ยวชาญ',
        '10' => 'รองผู้อํานวยการชํานาญการพิเศษ',
        '11' => 'รองผู้อํานวยการชํานาญการ',
        '12' => 'ศึกษานิเทศก์เชี่ยวชาญพิเศษ',
        '13' => 'ศึกษานิเทศก์เชี่ยวชาญ',
        '14' => 'ศึกษานิเทศก์ชํานาญการพิเศษ',
        '15' => 'ศึกษานิเทศก์ชํานาญการ',
        '16' => 'ครูเชี่ยวชาญพิเศษ',
        '17' => 'ครูเชี่ยวชาญ',
        '18' => 'ครูชํานาญการพิเศษ',
        '19' => 'ครูชํานาญการ'
    );

    private $_graduate = array(
        'Doctor Degree' => 'ปริญญาเอก',
        'Higher Master Degree' => 'ป.บัณฑิต สูงกว่า ป.โท',
        'Master Degree' => 'ปริญญาโท',
        'Higher Graduate Diploma' => 'ประกาศนียบัตรบัณฑิต/ชั้นสูง',
        'Higher Bachelor Degree' => 'ป.บัณฑิต สูงกว่า ป.ตรี',
        'Bachelor Degree' => 'ปริญญาตรี',
        'Diploma' => 'อนุปริญญา/เทียบเท่า',
        'Lower Diploma' => 'ต่ำกว่าอนุปริญญา',
        'N/A' => 'ไม่ระบุ',
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

    public function getRoom()
    {
        for ($roomNo = 1; $roomNo <= $this->maxRoomNumber ; $roomNo++) { 
            $arrayRoom['ห้อง ' . $roomNo] = 'ห้อง ' . $roomNo; 
        }

        return $arrayRoom;
    }

    public function getMembersType()
    {
        return $this->_peopleType;
    }

    public function getPrefixName()
    {
        foreach ($this->_prefixName3 as $_prefixName) {
            $arrayprefixName[$_prefixName['value']] = $_prefixName['label'];
        }
        return $arrayprefixName;
        return $this->_prefixName;
    }

    public function getPrefixNameEn()
    {
        foreach ($this->_prefixName3 as $_prefixName) {
            $arrayprefixName[$_prefixName['value']] = $_prefixName['label'];
        }
        return $arrayprefixName;
        return $this->_prefixNameEn;
    }

    public function getPrefixNameEnData()
    {
        return $this->_prefixNameEn;
    }

    public function getPrefixNameData()
    {
        return $this->_prefixName;
    }

    public function getItemLanguage()
    {
        return $this->_itemLanguage;
    }
    public function getDay()
    {
        $dayList = array();

        for ($dayCount = 1; $dayCount <= 31; $dayCount++ ) { 
            $dayList[$dayCount] = $dayCount;
        }
        return $dayList;
    }
    public function getMonth()
    {
        return $this->_month;
    }
    public function getShortMonth()
    {
        return $this->_shortMonth;
    }
    public function getYear()
    {
        $yearList = array();

        for ($yearCount = 0; $yearCount < 100; $yearCount++ ) { 
            $yearList[(date('Y')) - $yearCount] = (date('Y')) - $yearCount;
        }
        return $yearList;
    }

    public function getStatus()
    {
        return $this->_status;
    }

    public function getBorrowFineStatus()
    {
        return $this->_borrow_fine_status;
    }

    public function searchPrefixName($needle)
    {
        return $key = array_search($needle, $this->_prefixName); // $key = 2;
    }

    public function getBloodGroup()
    {
        return $this->_bloodGroup;
    }
    public function getAcademicStanding()
    {
        return $this->_academicStanding;
    }

    public function getGraduate()
    {
        return $this->_graduate;
    }

    public function getClientIP() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

}
