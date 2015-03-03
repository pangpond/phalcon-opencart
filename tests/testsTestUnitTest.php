<?php

namespace Test;

require_once 'UnitTestCase.php';

/**
 * Class UnitTest
 */
class UnitTest extends \UnitTestCase {

    public function testTestCase() {

        $this->assertEquals('works',
            'works',
            'This is OK'
        );

        $this->assertEquals('works',
            'works',
            'This wil fail'
        );

        // $str = 'มัธยม';
        // // echo mb_detect_encoding($str);
        
        // $this->assertEquals('มัธยม',
        //     mb_detect_encoding($str),
        //     'This wil fail'
        // );

        // $this->assertEquals('works',
        //     json_encode(iconv("utf-8", "utf-8", $str)),
        //     'This wil fail'
        // );
    }
    public function testFloat2Int() {

        $float = 50.00;
        $this->assertEquals('50',
            (int)$float,
            'This wil fail'
        );
    }
}