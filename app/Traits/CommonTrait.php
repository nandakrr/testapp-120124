<?php

namespace App\Traits;


  trait CommonTrait
  {
      public function curlRequest($method = '', $data = '', $url = '')
      {
        if ($method == 'POST' || $method == 'post') 
        {
          $ch = curl_init($url);
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLINFO_HEADER_OUT, true);
          $result = curl_exec($ch);
          curl_close($ch);
          return $result;
        }

        if ($method == 'GET' || $method == 'get') {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }
      }


      public function appCall($method = '', $data = '', $url = '') 
      {
        if ($method == 'POST' || $method == 'post') {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }

        if ($method == 'GET' || $method == 'get') {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }
      }

      public function appCalls($method = '', $data = '', $url = '')
      {
          if ($method == 'POST' || $method == 'post') {
              $ch = curl_init($url);
              curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
              curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              $result = curl_exec($ch);
              curl_close($ch);
              return $result;
          }

          if ($method == 'GET' || $method == 'get') {
              $ch = curl_init($url);
              curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
              // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

              $result = curl_exec($ch);
              curl_close($ch);
              return $result;
          }
      }
  }