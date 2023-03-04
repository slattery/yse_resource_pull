<?php

namespace Drupal\yse_resource_pull\Twig\Extension;

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

    $yse_resources_url = 'https://NOPE/';
    $yse_resource_types = [
      'courses' => '/YUP/',
    ];
    $yse_resource_mimes = [
      'courses' => 'text/html',
    ];

    if ($type && $key) {
      $req = $yse_resources_url . $key;
      $res = \Drupal::httpClient()->get($req, [
        'headers' => [
          'Content-Type' => 'application/x-www-form-urlencoded',
          'Accept' => $yse_resource_mimes[$type],
        ],
      ]);
      if ($res->getStatusCode() == 200){
        $data = $res->getBody()->getContents();
        return $data;
      }
      else {
        return '<br />';
      }
    }
    return '';
  }
}