<?php

$finder = PhpCsFixer\Finder::create()
    ->in([__DIR__.'/src', __DIR__.'/examples', __DIR__.'/tests']);

return (new PhpCsFixer\Config())
    ->setFinder($finder)
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_unused_imports' => true,
        'single_quote' => true,
        'trailing_comma_in_multiline' => true,
        'blank_line_before_statement' => ['statements' => ['return']],
        'no_extra_blank_lines' => [
            'tokens' => [
                'extra',
                'break',
                'continue',
                'return',
                'throw',
                'use',
                'parenthesis_brace_block',
                'square_brace_block',
                'curly_brace_block',
            ],
        ],
        'no_trailing_whitespace' => true,
        'no_whitespace_before_comma_in_array' => true,
        'no_whitespace_in_blank_line' => true,
        'ordered_imports' => true,
        'linebreak_after_opening_tag' => true,
        'concat_space' => ['spacing' => 'none'],
    ])
    ->setUsingCache(false);