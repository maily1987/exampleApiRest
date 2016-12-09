<?php

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'ordered_imports' => true,
        'phpdoc_order' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'linebreak_after_opening_tag' => true,
        'modernize_types_casting' => true,
        'no_useless_else' => true,
        'protected_to_private' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
        ->exclude(['app','bin','scripts','var','vendor','web'])
        ->in(__DIR__)
    )
;
