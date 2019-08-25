<?php
namespace App\Helpers;

class ConfigHelper {
     public static function getAuthDriverProviderConfig() {
          $provider = config('auth.guards.web.provider');
          $providerDriver = config("auth.providers.$provider.driver");

          return (object)array("provider" => $provider, "driver" => $providerDriver);
     }
}       
