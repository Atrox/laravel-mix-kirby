<?php
/**
 * Laravel Mix for Kirby
 */

if (! function_exists('mix')) {
  /**
   * Get the appropriate HTML tag with the right path for the (versioned) Mix file.
   *
   * @param  string  $path
   */
  function mix($path)
  {
    static $manifest;

    $manifest_path = c::get('mix.manifest', 'assets/mix-manifest.json');
    $assets_path = c::get('mix.assets', 'assets');

    if (!$manifest) {
      if (!f::exists($manifest_path)) {
        return response::error('the mix manifest could not be located');
      }

      $manifest = str::parse(f::read($manifest_path), 'json');
    }

    if (!array_key_exists($path, $manifest)) {
      return response::error('the path is not in the mix manifest');
    }

    $mixFilePath = $assets_path . $manifest[$path];
    $pathExtension = f::extension($path);

    if ('css' === $pathExtension) {
      $mixFileLink = css($mixFilePath);
    } elseif ('js' === $pathExtension) {
      $mixFileLink = js($mixFilePath);
    } else {
      // @TODO Throw an error
      // "File type not recognized"
      return response::error('file type not recognized');
    }

    return $mixFileLink;
  }
}
