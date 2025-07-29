<?php

namespace App;

use Masterminds\HTML5;

class HtmlChunker
{
    /**
     * Chunks HTML content into hierarchical sections based on headings and content elements.
     * 
     * @param string $html The HTML string to chunk
     * @return array Array of formatted chunks as strings
     */
    public static function chunk(string $html): array
    {
        $html5 = new HTML5();
        $dom = $html5->loadHTML($html);
        
        $chunks = [];
        $currentHeadings = [];
        $hasH1 = false;
        
        // Traverse the DOM tree
        self::traverseNode($dom->documentElement, $currentHeadings, $chunks, $hasH1);
        
        return $chunks;
    }
    
    /**
     * Recursively traverses DOM nodes to build chunks.
     * 
     * @param \DOMNode $node The current DOM node
     * @param array $currentHeadings Array of current headings at each level
     * @param array &$chunks Reference to the chunks array to populate
     * @param bool $hasH1 Whether we've seen an h1 element
     */
    private static function traverseNode(\DOMNode $node, array &$currentHeadings, array &$chunks, bool &$hasH1): void
    {
        if ($node->nodeType === XML_TEXT_NODE) {
            return; // Skip text nodes, we'll handle them in content elements
        }
        
        if ($node->nodeType !== XML_ELEMENT_NODE) {
            return; // Skip non-element nodes
        }
        
        $tagName = strtolower($node->nodeName);
        
        // Handle headings
        $level = self::getHeadingLevel($tagName);

        if ($level !== null) {
            $headingText = self::extractTextContent($node);
            
            if (!empty(trim($headingText))) {
                // Track if we've seen an h1
                if ($level === 1) {
                    $hasH1 = true;
                }
                
                // If this is h2 and we haven't seen an h1 yet, treat it as h1
                $adjustedLevel = $level;
                if ($level === 2 && !$hasH1) {
                    $adjustedLevel = 1;
                    // Clear any existing headings since this h2 should be treated as top-level
                    $currentHeadings = [];
                }
                
                // Update current headings array - ensure no trailing whitespace
                $currentHeadings[$adjustedLevel - 1] = trim($headingText);
                // Remove any deeper headings
                $currentHeadings = array_slice($currentHeadings, 0, $adjustedLevel);
            }
        }
        
        // Handle content elements
        if (self::isContentElement($tagName)) {
            $content = self::extractContent($node, $tagName);
            
            if (!empty(trim($content))) {
                $chunk = self::buildChunk($currentHeadings, $content);
                $chunks[] = $chunk;
            }
        }
        
        // Recursively process child nodes
        foreach ($node->childNodes as $childNode) {
            self::traverseNode($childNode, $currentHeadings, $chunks, $hasH1);
        }
    }
    
    /**
     * Determines if a tag is a content element that should be chunked.
     * 
     * @param string $tagName The tag name to check
     * @return bool True if it's a content element
     */
    private static function isContentElement(string $tagName): bool
    {
        // Current implementation: p, ul, ol
        // TODO: Could be expanded to include: div, section, article, aside, main, etc.
        return in_array($tagName, ['p', 'ul', 'ol']);
    }

    /**
     * Gets the heading level from a tag name, or null if not a heading.
     * 
     * @param string $tagName The tag name to check
     * @return int|null The heading level (1-6) or null if not a heading
     */
    private static function getHeadingLevel(string $tagName): ?int
    {
        if (preg_match('/^h([1-6])$/', $tagName, $matches)) {
            return (int)$matches[1];
        }
        
        return null;
    }
    
    /**
     * Extracts plain text content from a DOM node, removing all formatting.
     * 
     * @param \DOMNode $node The node to extract text from
     * @return string The plain text content
     */
    private static function extractTextContent(\DOMNode $node): string
    {
        $text = '';
        
        foreach ($node->childNodes as $childNode) {
            if ($childNode->nodeType === XML_TEXT_NODE) {
                $text .= $childNode->textContent;
            } elseif ($childNode->nodeType === XML_ELEMENT_NODE) {
                // Recursively extract text from child elements
                $text .= self::extractTextContent($childNode);
            }
        }
        
        // Normalize whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }
    
    /**
     * Extracts content from a DOM node based on its tag type.
     * 
     * @param \DOMNode $node The node to extract content from
     * @param string $tagName The tag name of the node
     * @return string The formatted content
     */
    private static function extractContent(\DOMNode $node, string $tagName): string
    {
        if ($tagName === 'ul') {
            return self::extractUnorderedList($node);
        }
        
        if ($tagName === 'ol') {
            return self::extractOrderedList($node);
        }
        
        // Default to text content for other elements
        return self::extractTextContent($node);
    }
    
    /**
     * Extracts unordered list items and formats them as markdown.
     * 
     * @param \DOMNode $node The ul node
     * @return string The formatted list
     */
    private static function extractUnorderedList(\DOMNode $node): string
    {
        $items = [];
        
        foreach ($node->childNodes as $childNode) {
            if ($childNode->nodeType === XML_ELEMENT_NODE && strtolower($childNode->nodeName) === 'li') {
                $text = self::extractTextContent($childNode);
                if (!empty(trim($text))) {
                    $items[] = '- ' . $text;
                }
            }
        }
        
        return implode("\\n", $items);
    }
    
    /**
     * Extracts ordered list items and formats them as markdown.
     * 
     * @param \DOMNode $node The ol node
     * @return string The formatted list
     */
    private static function extractOrderedList(\DOMNode $node): string
    {
        $items = [];
        $index = 1;
        
        foreach ($node->childNodes as $childNode) {
            if ($childNode->nodeType === XML_ELEMENT_NODE && strtolower($childNode->nodeName) === 'li') {
                $text = self::extractTextContent($childNode);
                if (!empty(trim($text))) {
                    $items[] = $index . '. ' . $text;
                    $index++;
                }
            }
        }
        
        return implode("\\n", $items);
    }

    /**
     * Builds a formatted chunk string from headings and content.
     * 
     * @param array $headings Array of headings at each level
     * @param string $content The content text
     * @return string The formatted chunk
     */
    private static function buildChunk(array $headings, string $content): string
    {
        $parts = [];
        
        // Add headings with markdown formatting
        foreach ($headings as $level => $heading) {
            $markdown = str_repeat('#', $level + 1) . ' ' . $heading;
            $parts[] = $markdown;
        }
        
        // Add content
        $parts[] = $content;
        
        return implode("\\n", $parts);
    }
}