<?php
namespace Phoenix\Controllers;

use Phalcon\Tag as Tag;
use Phoenix\Models\OrderWatch;


class OrderWatchController extends ControllerBase
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
        $this->getOrderWatch();
    }

    public function exportAction()
    {
        /** PHPExcel */
        include '../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
        /** PHPExcel_Writer_Excel2007 */
        include '../vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel5.php';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getActiveSheet()->setTitle('MembershipCard ' . date('Y-m-d'));

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Custimer ID')
            ->setCellValue('C1', 'Shipping Province')
            ->setCellValue('D1', 'Payment Province')
            ->setCellValue('E1', 'Firstname')
            ->setCellValue('F1', 'Lastname')
            // ->setCellValue('G1', 'Total')
            ->setCellValue('H1', '2014-01')
            ->setCellValue('I1', '2014-02')
            ->setCellValue('J1', '2014-03')
            ->setCellValue('K1', '2014-04')
            ->setCellValue('L1', '2014-05')
            ->setCellValue('M1', '2014-06')
            ->setCellValue('N1', '2014-07')
            ->setCellValue('O1', '2014-08')
            ->setCellValue('P1', '2014-09')
            ->setCellValue('Q1', '2014-10')
            ->setCellValue('R1', '2014-11')
            ->setCellValue('S1', '2014-12')
            ->setCellValue('T1', '2015-01')
            ->setCellValue('U1', '2015-02')
            ->setCellValue('V1', '2015-03');

        $orders = $this->getOrderWatch();

        $rowOrder = 1;
        foreach ($orders as $order) {
            

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . ($rowOrder + 1), ($rowOrder))
                ->setCellValue('B' . ($rowOrder + 1), $order['customer_id'])
                ->setCellValue('C' . ($rowOrder + 1), $order['shipping'])
                ->setCellValue('D' . ($rowOrder + 1), $order['payment'])
                ->setCellValue('E' . ($rowOrder + 1), $order['firstname'])
                ->setCellValue('F' . ($rowOrder + 1), $order['lastname'])
                // ->setCellValue('G' . ($rowOrder + 1), $order['total'])
                ->setCellValue('H' . ($rowOrder + 1), $order['2014-01'])
                ->setCellValue('I' . ($rowOrder + 1), $order['2014-02'])
                ->setCellValue('J' . ($rowOrder + 1), $order['2014-03'])
                ->setCellValue('K' . ($rowOrder + 1), $order['2014-04'])
                ->setCellValue('L' . ($rowOrder + 1), $order['2014-05'])
                ->setCellValue('M' . ($rowOrder + 1), $order['2014-06'])
                ->setCellValue('N' . ($rowOrder + 1), $order['2014-07'])
                ->setCellValue('O' . ($rowOrder + 1), $order['2014-08'])
                ->setCellValue('P' . ($rowOrder + 1), $order['2014-09'])
                ->setCellValue('Q' . ($rowOrder + 1), $order['2014-10'])
                ->setCellValue('R' . ($rowOrder + 1), $order['2014-11'])
                ->setCellValue('S' . ($rowOrder + 1), $order['2014-12'])
                ->setCellValue('T' . ($rowOrder + 1), $order['2015-01'])
                ->setCellValue('U' . ($rowOrder + 1), $order['2015-02'])
                ->setCellValue('V' . ($rowOrder + 1), $order['2015-03']);

                $rowOrder++;
        }

        // file name to output
        $fname = date("Ymd_his") . ".xls";

        // temp file name to save before output
        $temp_file = tempnam(sys_get_temp_dir(), 'phpexcel');

        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
        $objWriter->save($temp_file);

        $response = new \Phalcon\Http\Response();

        // Redirect output to a client’s web browser (Excel2005)
        $response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->setHeader('Content-Disposition', 'attachment;filename="' . $fname . '"');
        $response->setHeader('Cache-Control', 'max-age=0');

        // If you're serving to IE 9, then the following may be needed
        $response->setHeader('Cache-Control', 'max-age=1');

        //Set the content of the response
        $response->setContent(file_get_contents($temp_file));

        // delete temp file
        unlink($temp_file);

        //Return the response
        return $response;

        

    }

    public function getOrderWatch() {

        $this->view->disable();

        $queryOrders = "SELECT SUM(total) as monthly, customer_id, date_added, total, payment_zone, shipping_zone, order_id, firstname, lastname, email, telephone, fax, customer_group FROM Phoenix\Models\OrderWatch GROUP BY customer_id, YEAR(date_added), MONTH(date_added) HAVING SUM(total) > 1500 ORDER BY customer_id, date_added";

        $orders = $this->modelsManager->executeQuery($queryOrders);

        $rowTmp = '';
        $numRows = 0;
        foreach ($orders as $order) {
            if($order->customer_id != $rowTmp){
                $rowTmp = $order->customer_id;
                $numRows++;
            }
            $row[$numRows]['customer_id'] = $order->customer_id;
            $row[$numRows]['shipping'] = $order->shipping_zone;
            $row[$numRows]['payment'] = $order->payment_zone;
            $row[$numRows]['firstname'] = $order->firstname;
            $row[$numRows]['lastname'] = $order->lastname;
            $row[$numRows]['total'] = $order->monthly;


            $monthPeriod = substr($order->date_added, 0, 7);
            $row[$numRows][$monthPeriod] = $order->monthly;
            
        }

        if (count($row) == 0)
            return false;
        else
            return $row;
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