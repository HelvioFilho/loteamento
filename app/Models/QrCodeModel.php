<?php

namespace App\Models;

use CodeIgniter\Model;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeModel extends Model
{
  function generateCode($data, $fullPathImage, $label)
  {
    try {
      $result = Builder::create()
        ->writer(new PngWriter())
        ->writerOptions([])
        ->data($data)
        ->encoding(new Encoding('UTF-8'))
        ->errorCorrectionLevel(ErrorCorrectionLevel::High)
        ->size(300)
        ->margin(10)
        ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
        ->labelText($label)
        ->labelAlignment(LabelAlignment::Center)
        ->validateResult(false)
        ->build();

      $result->saveToFile($fullPathImage);

      return true;
    } catch (\Exception $e) {
      return false;
    }
  }
}
