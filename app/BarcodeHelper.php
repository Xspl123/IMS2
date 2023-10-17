<?php

use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;
use BaconQrCode\Common\Mode;
use BaconQrCode\Encoder\Encoder;

function generateBarcode()
{
    // Generate a random barcode value (e.g., a random string)
    $barcodeValue = Str::random(12); // You can adjust the length

    // Create a QR code instance with the barcode value and Mode
    $mode = new Mode\AlphaNumeric(); // Adjust the Mode as needed (e.g., Numeric, Byte, etc.)
    $qrCode = new AlphaNumeric($barcodeValue);

    // Render the QR code as an image
    $renderer = new Png();
    $renderer->setHeight(200);
    $renderer->setWidth(200);
    $writer = new Writer($renderer);
    $image = $writer->write($qrCode);

    // Save the barcode image to a location (e.g., public/barcodes folder)
    $imagePath = 'public/barcodes/' . $barcodeValue . '.png';
    file_put_contents(storage_path($imagePath), $image);

    return $barcodeValue;
}

