<?php

namespace App\Services;

use Exception;
use pCloud\Sdk\App as pCloudApp;
use pCloud\Sdk\File as pCloudFile;
use pCloud\Sdk\Request as pCloudRequest;
use Ramsey\Uuid\Uuid;

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
      $fileMetadata = self::$pCloudFile->upload($file, self::$defaultFolderIdUpload, $nameFileUuid);

      return $fileMetadata->metadata->fileid;
    } catch (Exception $e) {
      dd($e);

      return false;
    }
  }

  public static function getLink($fileId)
  {
    try {
      self::init();

      $getFileLink = self::$pCloudRequest->get('getfilelink', ['fileid' => $fileId]);

      if ($getFileLink) {
        return 'http://' . $getFileLink->hosts[0] . $getFileLink->path;
      }
    } catch (Exception $e) {
    }

    return false;
  }
}
