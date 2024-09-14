<?php

namespace App\Services;

use Exception;
use pCloud\Sdk\App as pCloudApp;
use pCloud\Sdk\File as pCloudFile;
use pCloud\Sdk\Request as pCloudRequest;
use Ramsey\Uuid\Uuid;
use stdClass;

class PCloudService
{
  private static $pCloudApp;
  private static $pCloudFile;
  private static $pCloudRequest;
  private static $defaultFolderIdUpload;

  // Inicializar a configuração
  private static function init()
  {
    if (self::$pCloudApp === null) {
      self::$defaultFolderIdUpload = env('PCLOUD_FOLDER_UPLOAD');

      self::$pCloudApp = new pCloudApp();
      self::$pCloudApp->setAccessToken(env('PCLOUD_ACCESS_TOKEN'));

      self::$pCloudFile = new pCloudFile(self::$pCloudApp);
      self::$pCloudRequest = new pCloudRequest(self::$pCloudApp);
    }
  }

  public static function upload($file, $nameFile)
  {
    try {
      $nameFileUuid = Uuid::uuid4() . '-' . $nameFile;

      self::init();
      $fileUploaded = self::$pCloudFile->upload($file, self::$defaultFolderIdUpload, $nameFileUuid);

      $fileMetadata = new stdClass();

      $fileMetadata->width = $fileUploaded->metadata->width;
      $fileMetadata->height = $fileUploaded->metadata->height;
      $fileMetadata->fileId = $fileUploaded->metadata->fileid;

      return $fileMetadata;
    } catch (Exception $e) {
      return false;
    }
  }

  public static function getPublicLinkFile($fileId)
  {
    try {
      self::init();

      $pCloudPubLink = self::$pCloudRequest->post('getfilepublink', ['fileid' => $fileId]);

      if ($pCloudPubLink) {
        $code = $pCloudPubLink->code;
        $width = $pCloudPubLink->metadata->width;
        $height = $pCloudPubLink->metadata->height;

        $link = 'https://api.pcloud.com/getpubthumb?code=' . $code . '&size=' . $width . 'x' . $height;

        return $link;
      }
    } catch (Exception $e) {
    }

    return false;
  }
}
