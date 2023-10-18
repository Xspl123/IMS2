<?php
// app/helpers.php

use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade as PDF;

use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
// use Swift_Attachment;
// use Swift_Message;
use Dompdf\Options;
use App\Mail\ChallanInvoiceEmail;
use Illuminate\Support\Facades\File;
use App\Models\Chalan;
use App\Models\SalesModel;

function sendInvoiceEmail($id) {
    $data = [
        'challanInvoice' => Chalan::find($id),
    ];

    $pdfOptions = [
        'isHtml5ParserEnabled' => true,
        'isPhpEnabled' => true,
    ];

    $pdfComWatermark = PDF::loadView('mailsend', $data, $pdfOptions);
    $pdfCustWatermark = PDF::loadView('mailsend', $data, $pdfOptions);

    $pdfComWatermark->getDomPDF()->getCanvas()->page_text(50, 750, 'Company use', null, 10, [255, 0, 0]);
    $pdfCustWatermark->getDomPDF()->getCanvas()->page_text(50, 750, 'Customer use', null, 10, [0, 0, 255]);

    $pdfComWatermarkPath = storage_path('app/tmp/invoice_com_watermark.pdf');
    $pdfComWatermark->save($pdfComWatermarkPath);

    $pdfCustWatermarkPath = storage_path('app/tmp/invoice_cust_watermark.pdf');
    $pdfCustWatermark->save($pdfCustWatermarkPath);

    Mail::send([], [], function ($message) use ($pdfComWatermarkPath) {
        $message->to(['manikant.verma@vert-age.com'])
            ->cc(['sahadev@vert-age.com','accounts@vert-age.com','accounts1@vert-age.com'])
            ->subject('Challan for Company')
            ->attach($pdfComWatermarkPath, [
                'as' => 'challan_invoice_com.pdf',
                'mime' => 'application/pdf',
            ])
            ->setBody(view('email_template')->render(), 'text/html');
    });

    Mail::send([], [], function ($message) use ($pdfCustWatermarkPath) {
        $message->to(['abhishek@vert-age.com'])
            ->subject('Challan for Customer')
            ->attach($pdfCustWatermarkPath, [
                'as' => 'challan_invoice_cust.pdf',
                'mime' => 'application/pdf',
            ])
            ->setBody(view('email_template')->render(), 'text/html');
    });

    return [
        'pdfComWatermarkPath' => $pdfComWatermarkPath,
        'pdfCustWatermarkPath' => $pdfCustWatermarkPath,
    ];
}

function sendChallanEmail($id) {
    $challanInvoice = [
        'challanInvoice' => SalesModel::find($id),
    ];

    $pdfOptions = [
        'isHtml5ParserEnabled' => true,
        'isPhpEnabled' => true,
        'page_size' => 'A4',
    ];

    $pdfComWatermarkPathChallan = PDF::loadView('saleChallanCreate', $challanInvoice, $pdfOptions);
    $pdfCustWatermark = PDF::loadView('saleChallanCreate', $challanInvoice, $pdfOptions);

    $pdfComWatermarkPathChallan->getDomPDF()->getCanvas()->page_text(50, 750, 'Company use', null, 10, [255, 0, 0]);
    $pdfCustWatermark->getDomPDF()->getCanvas()->page_text(50, 750, 'Customer use', null, 10, [0, 0, 255]);

    $pdfComWatermarkPathChallanFile = storage_path('app/tmp/challan_com_watermark.pdf');
    $pdfComWatermarkPathChallan->save($pdfComWatermarkPathChallanFile);

    $pdfCustWatermarkPathChallanFile = storage_path('app/tmp/challan_cust_watermark.pdf');
    $pdfCustWatermark->save($pdfCustWatermarkPathChallanFile);

    Mail::send([], [], function ($message) use ($pdfComWatermarkPathChallanFile) {
        $message->to(['manikant.verma@vert-age.com'])
            ->cc(['sahadev@vert-age.com','accounts@vert-age.com','accounts1@vert-age.com'])
            ->subject('Challan for Company')
            ->attach($pdfComWatermarkPathChallanFile, [ // Use the file path here
                'as' => 'challan_com1.pdf',
                'mime' => 'application/pdf',
            ])
            ->setBody(view('email_template')->render(), 'text/html');
    });

    Mail::send([], [], function ($message) use ($pdfCustWatermarkPathChallanFile) {
        $message->to('abhishek@vert-age.com')
            ->subject('Challan for Customer')
            ->attach($pdfCustWatermarkPathChallanFile, [ // Use the file path here
                'as' => 'challan_cust.pdf1',
                'mime' => 'application/pdf',
            ])
            ->setBody(view('email_template')->render(), 'text/html');
    });

    return [
        'pdfComWatermarkPathChallan' => $pdfComWatermarkPathChallanFile,
        'pdfCustWatermarkPathChallan' => $pdfCustWatermarkPathChallanFile,
    ];
}