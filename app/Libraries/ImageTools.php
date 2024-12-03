<?php

namespace App\Libraries;

class ImageTools
{
  public function load($file, $name)
  {
    $image = \Config\Services::image();

    $filterName = mb_url_title($name, '-', true);

    $rand = mt_rand(1000, 9999);
    $imageName = 'img_' . $rand . '-' . $filterName . '_' . uniqid('img_') . '.jpg';
    try {
      $image
        ->withFile($file)
        ->fit(700, 700, 'center')
        ->convert(IMAGETYPE_JPEG)
        ->save($_ENV['IMAGE_PATH_TEMP'] . $imageName);
      return [
        'success' => true,
        'imageName' => $imageName
      ];
    } catch (\Exception $e) {
      return [
        'success' => false,
        'message' => 'Ocorreu um erro ao tentar salvar a imagem. Tente novamente mais tarde.'
      ];
    }
  }

  public function cleanTempFolder()
  {
    $files = glob($_ENV['IMAGE_PATH_TEMP'] . '*');
    foreach ($files as $file) {
      if (is_file($file))
        unlink($file);
    }
  }

  public function moveImages($images)
  {
    $directoryTemp = $_ENV['IMAGE_PATH_TEMP'];
    $directoryProducts = $_ENV['IMAGE_PATH'];

    foreach ($images as $imageName) {
      $oldPath = $directoryTemp . $imageName;
      $newPath = $directoryProducts . $imageName;
      if (!rename($oldPath, $newPath)) {
        // Tratar erro ao mover uma das imagens
      }
    }
  }

  public function deleteImage($imageName, $directory)
  {
    $path = $directory . $imageName;
    if (file_exists($path)) {
      unlink($path);
    }
  }
}
