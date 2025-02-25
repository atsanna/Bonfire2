<?php

/**
 * This file is part of Bonfire.
 *
 * (c) Lonnie Ezell <lonnieje@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * php version 8.0
 *
 * @category Menus
 * @license  MIT https://opensource.org/licenses/MIT
 * @see      https://github.com/lonnieezell/Bonfire2/
 */

namespace Bonfire\Menus;

/**
 * Trait HasMenuIcons
 */
trait HasMenuIcons
{
    /**
     * FontAwesome 5 icon name
     */
    protected ?string $faIcon;

    /**
     * URL of the icon, if it's an image.
     */
    protected ?string $iconUrl;

    /**
     * Sets the FontAwesome icon name
     *
     * Here is an inline example:
     *
     * ```js
     * setFontAwesomeIcon('fa-pencil');
     * setFontAwesomeIcon('fal fa-alarm-clock');
     * ```
     *
     * @param string $iconName FontAwesome Icon Class name
     *
     * @return self
     */
    public function setFontAwesomeIcon(string $iconName)
    {
        $this->faIcon = $iconName;

        return $this;
    }

    /**
     * Sets the URL to the icon, if it's an image.
     *
     * @param string $url Custom Icon URL
     *
     * @return self
     */
    public function setIconUrl(string $url)
    {
        $this->iconUrl = $url;

        return $this;
    }

    /**
     * Returns HTML tag depends Menu Icon type `iconUrl` or `faIcon`
     *
     * Returns the full icon tag: either a `<i>` tag for FontAwesome icons,
     * or an `<img>` tag for images.
     *
     * @param string $classOrIconUrl FontAwesome Icon Class Name or Icon URL
     */
    public function icon(string $classOrIconUrl = ''): string
    {
        if (! empty($this->faIcon)) {
            return $this->buildFontAwesomeIconTag($classOrIconUrl);
        }
        if (! empty($this->iconUrl)) {
            return $this->buildImageIconTag($classOrIconUrl);
        }

        return '';
    }

    /**
     * Returns FontAwesome HTML Icon Tag
     *
     * Eg: `<i class="fas fa-users"></i>`
     *
     * @param string $className Custom class name for FontAwesome Icon
     */
    protected function buildFontAwesomeIconTag(string $className): string
    {
        $class = $className === '' || $className === '0' ? '' : " {$className}";

        return "<i class=\"{$this->faIcon}{$class}\"></i>";
    }

    /**
     * Returns Image tag for custom icon
     *
     * Eg: `<img src="https://example.com" class="some-class"/>`
     *
     * @param string $class Custom Icon class name
     */
    protected function buildImageIconTag(string $class): string
    {
        $class = $class === '' || $class === '0' ? '' : "class=\"{$class}\" ";

        $iconUrl = str_contains($this->iconUrl, '://')
            ? $this->iconUrl
            : '/' . ltrim($this->iconUrl, '/ ');

        return "<img href=\"{$iconUrl}\" alt=\"{$this->title}\" {$class}/>";
    }
}
