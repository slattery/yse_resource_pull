<?php

namespace Drupal\yse_resource_pull\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Provides filters for Twig.
 */
class Filters extends AbstractExtension {

  /**
   * {@inheritdoc}
   */
  public function getFilters(): array {
    return [
      new TwigFilter('ysestriptags', [$this, 'getStripTags']),
    ];
  }

  /**
   * Strips HTML from a string.
   *
   * @param string $string
   *.  The HTML content.
   *
   * @return string
   *   The clean string.
   */
  public function getStripTags(string $html): string {
    return strip_tags($html);
  }

}