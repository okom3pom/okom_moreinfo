<?php
/*
This file is part of PHP CS Fixer.

(c) Fabien Potencier <fabien@symfony.com>
    Dariusz Rumiński <dariusz.ruminski@gmail.com>

This source file is subject to the MIT license that is bundled
with this source code in the file LICENSE.
*/

$header = <<<'EOF'
Module : Question on product for Prestashop 1.6.X

MIT License

Copyright (c) 2018

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.


@author    Okom3pom <contact@okom3pom.com>
@copyright 2008-2018 Okom3pom
@version   2.0.1
@license   Free
EOF;

$finder = PhpCsFixer\Finder::create()
    ->exclude('tests/Fixtures')
    ->in(__DIR__)
;

$config = PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
      '@PHP56Migration' => true,
      '@PHPUnit60Migration:risky' => true,
      '@Symfony' => true,
      '@Symfony:risky' => true,
      'align_multiline_comment' => true,
      'array_indentation' => true,
      'array_syntax' => ['syntax' => 'short'],
      'blank_line_before_statement' => true,
      'combine_consecutive_issets' => true,
      'combine_consecutive_unsets' => true,
      'comment_to_phpdoc' => true,
      'compact_nullable_typehint' => true,
      'escape_implicit_backslashes' => true,
      'explicit_indirect_variable' => true,
      'explicit_string_variable' => true,
      'final_internal_class' => true,
      'fully_qualified_strict_types' => true,
      'function_to_constant' => ['functions' => ['get_class', 'get_called_class', 'php_sapi_name', 'phpversion', 'pi']],
      'header_comment' => ['header' => $header],
      'heredoc_to_nowdoc' => true,
      'list_syntax' => ['syntax' => 'long'],
      'logical_operators' => true,
      'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
      'method_chaining_indentation' => true,
      'multiline_comment_opening_closing' => true,
      'no_alternative_syntax' => true,
      'no_binary_string' => true,
      'no_extra_blank_lines' => ['tokens' => ['break', 'continue', 'extra', 'return', 'throw', 'use', 'parenthesis_brace_block', 'square_brace_block', 'curly_brace_block']],
      'no_null_property_initialization' => true,
      'no_short_echo_tag' => true,
      'no_superfluous_elseif' => true,
      'no_unneeded_curly_braces' => true,
      'no_unneeded_final_method' => true,
      'no_unreachable_default_argument_value' => true,
      'no_unset_on_property' => true,
      'no_useless_else' => true,
      'no_useless_return' => true,
      'ordered_class_elements' => true,
      'ordered_imports' => true,
      'php_unit_internal_class' => true,
      'php_unit_ordered_covers' => true,
      'php_unit_set_up_tear_down_visibility' => true,
      'php_unit_strict' => true,
      'php_unit_test_annotation' => true,
      'php_unit_test_case_static_method_calls' => ['call_type' => 'this'],
      'php_unit_test_class_requires_covers' => true,
      'phpdoc_add_missing_param_annotation' => true,
      'phpdoc_order' => true,
      'phpdoc_trim_consecutive_blank_line_separation' => true,
      'phpdoc_types_order' => true,
      'return_assignment' => true,
      'semicolon_after_instruction' => true,
      'single_line_comment_style' => true,
      'strict_comparison' => true,
      'strict_param' => true,
      'string_line_ending' => true,
      'yoda_style' => true,
    ])
    ->setFinder($finder)
;

// special handling of fabbot.io service if it's using too old PHP CS Fixer version
if (false !== getenv('FABBOT_IO')) {
    try {
        PhpCsFixer\FixerFactory::create()
            ->registerBuiltInFixers()
            ->registerCustomFixers($config->getCustomFixers())
            ->useRuleSet(new PhpCsFixer\RuleSet($config->getRules()));
    } catch (PhpCsFixer\ConfigurationException\InvalidConfigurationException $e) {
        $config->setRules([]);
    } catch (UnexpectedValueException $e) {
        $config->setRules([]);
    } catch (InvalidArgumentException $e) {
        $config->setRules([]);
    }
}

return $config;
