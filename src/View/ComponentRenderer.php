<?php

/**
 * This file is part of Bonfire.
 *
 * (c) Lonnie Ezell <lonnieje@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bonfire\View;

use RuntimeException;
use Throwable;

class ComponentRenderer
{
    public function __construct()
    {
        helper('inflector');
        ini_set('pcre.backtrack_limit', '-1');
    }

    /**
     * Examines the given string and parses any view components,
     * returning the modified string.
     *
     * Called by the View class' render method.
     */
    public function render(?string $output): string
    {
        if ($output === null || $output === '' || $output === '0') {
            return $output;
        }

        // Try to locate any custom tags, with names like: x-sidebar, x-btn, etc.
        service('timer')->start('self-closing');
        $output = $this->renderSelfClosingTags($output);
        service('timer')->stop('self-closing');
        service('timer')->start('paired-tags');

        $output = $this->renderPairedTags($output);
        service('timer')->stop('paired-tags');

        return $output;
    }

    /**
     * Finds and renders and self-closing tags, i.e. <x-foo />
     */
    private function renderSelfClosingTags(string $output): string
    {
        // Pattern borrowed from Laravel's ComponentTagCompiler
        $pattern = "/
            <
                \\s*
                x[-\\:](?<name>[\\w\\-\\:\\.]*)
                \\s*
                (?<attributes>
                    (?:
                        \\s+
                        (?:
                            (?:
                                \\{\\{\\s*\\\$attributes(?:[^}]+?)?\\s*\\}\\}
                            )
                            |
                            (?:
                                [\\w\\-:.@]+
                                (
                                    =
                                    (?:
                                        \\\"[^\\\"]*\\\"
                                        |
                                        \\'[^\\']*\\'
                                        |
                                        [^\\'\\\"=<>]+
                                    )
                                )?
                            )
                        )
                    )*
                    \\s*
                )
            \\/>
        /x";

        /*
            $matches[0] = full tags matched
            $matches[name] = tag name (minus the 'x-')
            $matches[attributes] = array of attribute string (class="foo")
         */
        return preg_replace_callback($pattern, function ($match) {
            $view       = $this->locateView($match['name']);
            $attributes = $this->parseAttributes($match['attributes']);
            $component  = $this->factory($match['name'], $view);

            return $component instanceof Component
                ? $component->withView($view)->render()
                : $this->renderView($view, $attributes);
        }, $output);
    }

    private function renderPairedTags(string $output): string
    {
        $pattern = '/(?(DEFINE)(?<marker>x-))
                        <\g<marker>(?<name>\w[\w\-\:\.]+[^\>\/\s\/])
                                   (?<attributes>[\s\S\=\'\"]+?)??>
                            (?(?<!\/>) # Not paired so ignore the rest
                                (?<slot>.*?)??
                        <\/\g<marker>\k<name>\s*>
                            )
                    /uismx';

        /*
            $match['name']       = tag name (minus the `x-`)
            $match['attributes'] = string of tag attributes (class="foo")
            $match['slot']       = the content inside the tags
        */

        do {
            try {
                $output = preg_replace_callback($pattern, function ($match) {
                    $view               = $this->locateView($match['name']);
                    $attributes         = $this->parseAttributes($match['attributes']);
                    $attributes['slot'] = $match['slot'];
                    $component          = $this->factory($match['name'], $view);

                    return $component instanceof Component
                        ? $component->withView($view)->withData($attributes)->render()
                        : $this->renderView($view, $attributes);
                }, (string) $output, -1, $replaceCount);
            } catch (Throwable) {
                break;
            }
        } while ($replaceCount !== 0);

        return $output ?? preg_last_error();
    }

    /**
     * Parses a string to grab any key/value pairs, HTML attributes.
     */
    private function parseAttributes(string $attributeString): array
    {
        // Pattern borrowed from Laravel's ComponentTagCompiler
        $pattern = '/
            (?<attribute>[\w\-:.@]+)
            (
                =
                (?<value>
                    (
                        \"[^\"]+\"
                        |
                        \\\'[^\\\']+\\\'
                        |
                        [^\s>]+
                    )
                )
            )?
        /x';

        if (! preg_match_all($pattern, $attributeString, $matches, PREG_SET_ORDER)) {
            return [];
        }

        $attributes = [];

        foreach ($matches as $match) {
            $attributes[$match['attribute']] = $this->stripQuotes($match['value']);
        }

        return $attributes;
    }

    /**
     * Renders the view when no corresponding class has been found.
     */
    private function renderView(string $view, array $data): string
    {
        return (static function (string $view, $data) {
            extract($data);
            ob_start();
            eval('?>' . file_get_contents($view));

            return ob_get_clean() ?: '';
        })($view, $data);
    }

    /**
     * Attempts to locate the view and/or class that
     * will be used to render this component. By default,
     * the only thing that is needed is a view, but a
     * Component class can also be found if more power is needed.
     *
     * If a class is used, the name is expected to be
     * <viewName>Component.php
     */
    private function factory(string $name, string $view): ?Component
    {
        // Locate the class in the same folder as the view
        $class    = pascalize(str_replace('-', '_', $name)) . 'Component.php';
        $filePath = str_replace($name . '.php', $class, $view);

        if (empty($filePath)) {
            return null;
        }

        if (! file_exists($filePath)) {
            return null;
        }
        $className = service('locator')->getClassname($filePath);

        if (! class_exists($className)) {
            include_once $filePath;
        }

        return (new $className())->withView($view);
    }

    /**
     * Locate the view file used to render the component.
     * The file's name must match the name of the component,
     * minus the 'x-'.
     */
    private function locateView(string $name): string
    {
        // First search within the current theme
        $path     = Theme::path();
        $filePath = $path . 'Components/' . $name . '.php';

        if (is_file($filePath)) {
            return $filePath;
        }

        // fallback: check in components' default lookup paths from config
        $componentsLookupPaths = config('Themes')->componentsLookupPaths;

        foreach ($componentsLookupPaths as $componentPath) {
            $filePath = $componentPath . $name . '.php';

            if (is_file($filePath)) {
                return $filePath;
            }
        }

        throw new RuntimeException('View not found for component: ' . $name);
        // @todo look in all normal namespaces
    }

    /**
     * Removes surrounding quotes from a string.
     */
    private function stripQuotes(string $string): string
    {
        return trim($string, "\\'\"");
    }
}
