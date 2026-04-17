<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
    ->exclude('vendor') // 👈 důležité!
    ->notPath([
        'config/bundles.php',
        'config/reference.php',
    ]);

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,

        // 👇 pár užitečných tweaků
        'array_syntax' => ['syntax' => 'short'], // []
        'ordered_imports' => true,              // use řazení
        'no_unused_imports' => true,            // odstraní nepoužité use
    ])
    ->setFinder($finder);