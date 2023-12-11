<?php
error_reporting(E_ALL ^ E_NOTICE);
ob_start();
session_start();

define('DB_HOST', "localhost");
define('DB_USER', "root");
define('DB_PASS', "");
define('DB_Name', "angular");

function connect()
{
    $connect = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_Name);

    if (mysqli_connect_errno()) {
        die("Fail to connect" . mysqli_connect_error());
    }

    return $connect;
}

$con = connect();  // call function

require('tcpdf.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);


$pdf->AddPage();
// Header part
$pdf->setFont('helvetica', 'B', 36);
$pdf->cell(0, 22, 'Laxmi academy Laxmi', 0, 1, 'C', 0, '', false, 'M', 'M');

$pdf->setFont('helvetica', 'B', 14);
$pdf->cell(0, 15, 'Rampur Road, Shivneri nagar', 0, 1, 'C', 0, '', false, 'M', 'M');
$pdf->cell(0, 15, 'Degloor District Nanded', 0, 1, 'C', 0, '', false, 'M', 'M');

$pdf->setFont('helvetica', 'B', 12);
$pdf->cell(72, 8, 'Email : hmamjad999@gmail.com', 0, 0, 'C', 0, '', false, 'M', 'M');
$pdf->cell(50, 8, 'Mobile : 01776102769', 0, 0, 'C', 0, '', false, 'M', 'M');
$pdf->cell(72, 8, 'Website : https://jswebapp.com/', 0, 1, 'C', 0, '', false, 'M', 'M');

// line part
$pdf->line(8, 49, 200, 49);
$pdf->line(8, 50, 200, 50);



// Date and address part
$pdf->setFont('times', 'BI', 12);
$pdf->ln(15);
$pdf->cell(180, 8, 'Date : ' . date('j/n/Y'), 0, 1, 'R', 0, '', false, 'M', 'M');


$pdf->ln(3);
$pdf->cell(90, 10, 'Class : One', 0, 0, 'L', 0, '', false, 'M', 'M');
$pdf->cell(90, 10, 'Student NO : 10', 0, 1, 'L', 0, '', false, 'M', 'M');

$pdf->ln(3);
$pdf->cell(90, 10, 'Batch : One', 0, 0, 'L', 0, '', false, 'M', 'M');
$pdf->cell(90, 10, 'Medium : 10', 0, 1, 'L', 0, '', false, 'M', 'M');

$pdf->ln(3);
$pdf->cell(90, 10, 'Gender : Male', 0, 0, 'L', 0, '', false, 'M', 'M');
$pdf->cell(90, 10, 'Mobile : 01776102769', 0, 1, 'L', 0, '', false, 'M', 'M');

$pdf->ln(3);
$pdf->cell(90, 10, 'Name : Amjad', 0, 0, 'L', 0, '', false, 'M', 'M');
$pdf->cell(90, 10, 'School : Daulatpur High School', 0, 1, 'L', 0, '', false, 'M', 'M');



// Table part
$pdf->setFont('times', '', 12);
$pdf->ln(3);

$tbl = <<<EOD
       <table border="1" cellpadding="2" cellspacing="2">

       <tr>
       <th colspan="4" align="center" style="font-size:18px; font-weight:bold">FEE DETAILS</th>
       </tr>

       <tr>
       <td width="10%"  style="text-align:center,vertical-align:middle; font-weight:bold">SL No</td>
       <td width="20%"  style="text-align:center,vertical-align:middle; font-weight:bold">Particulars</td>
       <td width="20%"  style="text-align:center,vertical-align:middle; font-weight:bold">Date</td>
       <td width="20%"  style="text-align:center,vertical-align:middle; font-weight:bold">Voucher No</td>
       <td width="30%"  style="text-align:center,vertical-align:middle; font-weight:bold">Amount</td>
       </tr>


       
EOD;

$sql = "SELECT * FROM fee";

if ($result = mysqli_query($con, $sql)) {
    $i = 0;

    $totalFee = 0;


    while ($row = mysqli_fetch_assoc($result)) {


        if (isset($row['feeFeeAmt'])) {
            $fee = $row['feeFeeAmt'];
        }
        if (isset($row['feeVoucherNo'])) {
            $feeVoucherNo = $row['feeVoucherNo'];
        }
        if (isset($row['feeDate'])) {
            $feeDate = $row['feeDate'];

            $newDate = date("d/m/Y", strtotime($feeDate));
        }

        $totalFee += (int)$fee;

        $tbl .= <<<EOD
        <tr>
        <td width="10%"  style="text-align:center,vertical-align:middle; font-weight:bold"> $i </td>
        <td width="20%"  style="text-align:center,vertical-align:middle; font-weight:bold">Tution Fee</td>
        <td width="20%"  style="text-align:center,vertical-align:middle; font-weight:bold">$newDate</td>
        <td width="20%"  style="text-align:center,vertical-align:middle; font-weight:bold">$feeVoucherNo </td>
        <td width="30%"  style="text-align:center,vertical-align:middle; font-weight:bold">$fee</td>
        </tr>
        EOD;

        $i++;
    }
}

$tbl .= <<<EOD
<tr>
<td style="text-align:right,font-weight:bold" colspan="4">Total</td>
<td style="text-align:center,vertical-align:middle">$totalFee</td>
</tr>
</table>
EOD;



$pdf->writeHTML($tbl, true, false, false, false, '');


$pdf->ln(20);
$pdf->setFont('helvetica', '', 12);
$pdf->cell(10, 20, 'In word :', 0, 0, 'L', 0, '', false, 'M', 'M');



$pdf->setFont('helvetica', 'B', 12);
$pdf->cell(50, 15, 'Seven Hundred Tk', 0, 1, 'R', 0, '', false, 'M', 'M');
// convert number to word link show

$pdf->setFont('helvetica', '', 12);
$pdf->cell(40, 10, 'Recieve with Thanks', 0, 1, 'L', 0, '', false, 'M', 'M');

$pdf->ln(8);
$pdf->setFont('helvetica', '', 12);
$pdf->cell(0, 10, 'Author Signature', 0, 1, 'R', 0, '', false, 'M', 'M');

$pdf->ln(8);
$pdf->setFont('helvetica', 'B', 12);
$pdf->cell(15, 10, 'Note :', 0, 0, 'L', 0, '', false, 'M', 'M');

$pdf->setFont('helvetica', '', 12);
$pdf->cell(40, 10, 'Here will note text', 0, 1, 'L', 0, '', false, 'M', 'M');


$pdf->Output('example.pdf', "I");

// $w: Width of the cell.
// $h: Height of the cell. If 0, the cell extends up to the right margin.
// $txt: Text to be displayed within the cell.
// $border: Indicates if borders should be drawn around the cell. Default is 0 (no border).
// $ln: Indicates where the current position should go after the call. Default is 0 (to the right).
// $align: Alignment of the text. Possible values are 'L' (left), 'C' (center), and 'R' (right).
// $fill: If true, the cell is filled with the background color.
// $link: URL or identifier for an internal link.
