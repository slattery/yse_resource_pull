<?php

namespace Drupal\yse_resource_pull\Twig\Extension;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;


/**
 * Functions provides functions.
 */
class Functions extends AbstractExtension {

  /**
   * Declare your custom twig function here.
   *
   * @return \Twig\TwigFunction[]
   *   TwigFunction array.
   */

  public function getFunctions() {
    return [
      new TwigFunction('get_yse_resource',
        [$this, 'getYseResource'],
        ['is_safe' => ['html']]
      ),
    ];
  }

  /**
   * Provides HTML (should render array).
   *
   * @param string $type
   *   courses, rooms, etc.
   * @param string $key
   *   arg for resource server query
   *
   * @return array|string
   *   NUll or render array of block.
   */
  public function getYseResource(string $type, string $key) {
    if ($type && $key) {

      $config = \Drupal::config('yse_resource_pull.settings');
      $resource_host   = $config->get('resource_host');
      $resource_path   = $config->get('resource_paths.' . $type);
      $resource_mime   = $config->get('resource_mimes.' . $type);
      $resource_apikey = $config->get('resource_apikey');
      
      if ((!empty($resource_host)) && (!empty($resource_path))){

        $yse_resources_url = 'https://' . $resource_host . $resource_path . $key;

        if (!empty($resource_apikey)){
          $yse_resources_url .= '?apikey=' . $resource_apikey;
        }

        $guzzle = new Client(
          ['headers' => [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => $resource_mime
          ]
        ]);

        $req = $yse_resources_url;

        try {
          $res = $guzzle->request('GET', $req);
          $data = $res->getBody()->getContents();
          return $data;
        }
        catch (ConnectException $e) {
          return '<-- Courses 4xx -->';
        }
        catch (RequestException $e) {
          return '<-- Courses 4xx -->';
        }
        catch (ClientException $e) {
          return '<-- Courses 4xx -->';
        }
      }
    }
    return '';
  }
}