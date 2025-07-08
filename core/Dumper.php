<?php

declare(strict_types=1);

/**
 * Coreon Framework (c) 2025 Mikael Oliveira
 * Licensed under the MIT License.
 */

namespace Core;

class Dumper
{
    function dump(mixed $var): void
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $caller = $backtrace[1] ?? null;
        $file = $caller['file'] ?? 'unknown file';
        $line = $caller['line'] ?? 'unknown line';

        echo '<style>
        .gruvbox-pre { background:#282828; color:#ebdbb2; padding:15px 20px; border-radius:5px; font-family: Consolas, Monaco, monospace; font-size:14px; line-height:1.6; white-space: pre-wrap; word-break: break-word; }
        .gruvbox-key { color:#fabd2f; }
        .gruvbox-type { color:#8ec07c; }
        .gruvbox-string { color:#d3869b; }
        .gruvbox-number { color:#fe8019; }
        .gruvbox-bool { color:#83a598; }
        .gruvbox-null { color:#a89984; }
        </style>';
        
        echo '<pre class="gruvbox-pre">';
        echo '<div style="background:#3c3836;color:#fabd2f;padding:10px;border-radius:5px;font-family: Consolas, Monaco, monospace; margin-bottom:10px;">';
        echo "Called from <strong>$file</strong> on line <strong>$line</strong>";
        echo '</div>';
        echo $this->gruvbox_dump($var);
        echo '</pre>';
        die;
    }

    function dumpEloquent(mixed $var): void
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $caller = $backtrace[2] ?? $backtrace[1] ?? null;
        $file = $caller['file'] ?? 'unknown file';
        $line = $caller['line'] ?? 'unknown line';

        echo '<style>
        .gruvbox-pre { background:#282828; color:#ebdbb2; padding:15px 20px; border-radius:5px; font-family: Consolas, Monaco, monospace; font-size:14px; line-height:1.6; white-space: pre-wrap; word-break: break-word; }
        .gruvbox-key { color:#fabd2f; }
        .gruvbox-type { color:#8ec07c; }
        .gruvbox-string { color:#d3869b; }
        .gruvbox-number { color:#fe8019; }
        .gruvbox-bool { color:#83a598; }
        .gruvbox-null { color:#a89984; }
        </style>';
        
        echo '<pre class="gruvbox-pre">';
        echo '<div style="background:#3c3836;color:#fabd2f;padding:10px;border-radius:5px;font-family: Consolas, Monaco, monospace; margin-bottom:10px;">';
        echo "Called from <strong>$file</strong> on line <strong>$line</strong>";
        echo '</div>';
        echo $this->gruvbox_dump($var);
        echo '</pre>';
        die;
    }
    
    private function gruvbox_dump(mixed $var, int $indent = 0): string
    {
        $indentStr = str_repeat('  ', $indent);
        $output = '';
    
        if (is_array($var)) {
            $output .= "array(" . count($var) . ") {\n";
            foreach ($var as $key => $value) {
                $output .= $indentStr . '  <span class="gruvbox-key">["' . htmlspecialchars((string)$key) . '"]=> </span>';
                if (is_array($value) || is_object($value)) {
                    $output .= "\n" . $this->gruvbox_dump($value, $indent + 2);
                } else {
                    $output .= $this->gruvbox_type_value($value) . "\n";
                }
            }
            $output .= $indentStr . "}";
        } elseif (is_object($var)) {
            $class = get_class($var);
            $output .= "object(<span class=\"gruvbox-type\">$class</span>) {\n";
            foreach (get_object_vars($var) as $key => $value) {
                $output .= $indentStr . '  <span class="gruvbox-key">["' . htmlspecialchars((string)$key) . '"]=> </span>';
                if (is_array($value) || is_object($value)) {
                    $output .= "\n" . $this->gruvbox_dump($value, $indent + 2);
                } else {
                    $output .= $this->gruvbox_type_value($value) . "\n";
                }
            }
            $output .= $indentStr . "}";
        } else {
            $output .= $this->gruvbox_type_value($var);
        }
    
        return $output;
    }
    
    private function gruvbox_type_value(mixed $value): string
    {
        switch (gettype($value)) {
            case 'string':
                return '<span class="gruvbox-type">string</span>(' . strlen($value) . ') <span class="gruvbox-string">"' . htmlspecialchars($value) . '"</span>';
            case 'int':
                return '<span class="gruvbox-type">int</span>(<span class="gruvbox-number">' . $value . '</span>)';
            case 'float':
                return '<span class="gruvbox-type">float</span>(<span class="gruvbox-number">' . $value . '</span>)';
            case 'bool':
                return '<span class="gruvbox-type">bool</span>(<span class="gruvbox-bool">' . ($value ? 'true' : 'false') . '</span>)';
            case 'NULL':
                return '<span class="gruvbox-null">NULL</span>';
            default:
                return '<span class="gruvbox-type">' . gettype($value) . '</span>';
        }
    }
}