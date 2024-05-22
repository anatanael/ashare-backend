<?php

namespace AshareApp\Service;

class ImgurService
{
  private $URI_IMGUR;
  private $IMGUR_CLIENT_ID;

  public function __construct()
  {
    $this->URI_IMGUR = 'https://api.imgur.com/3/image';
    $this->IMGUR_CLIENT_ID = $_ENV['IMGUR_CLIENT_ID'];
  }

  public function uploadImage($image)
  {
    $imageData = $image->getStream()->getContents();

    $data = [
      'image' => $imageData,
    ];

    $ch = curl_init($this->URI_IMGUR);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Client-ID $this->IMGUR_CLIENT_ID"]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $response = json_decode($response, true);
    curl_close($ch);

    return $response;
  }
}
