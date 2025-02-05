<?php

declare(strict_types=1);

/**
 * This file is part of Bonfire.
 *
 * (c) Lonnie Ezell <lonnieje@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->files()
    ->in([
        __DIR__ . '/../src/',
        __DIR__ . '/../tests/',
    ])
    ->exclude([
        'build',
        'Views',
    ])
    ->append([
        __FILE__,
        __DIR__ . '/rector.php',
    ]);

return (new Config())
    ->setRules([
        '@PSR12'                 => true,
        'array_syntax'           => ['syntax' => 'short'],
        'binary_operator_spaces' => [
            'operators' => [
                '=>' => 'align_single_space_minimal',
                '='  => 'align_single_space_minimal',
            ],
        ],
        'line_ending' => false,
    ])
    ->setCacheFile(__DIR__ . '/../build/.php-cs-fixer.cache')
    ->setFinder($finder)
;
