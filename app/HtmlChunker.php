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
        
        // Traverse the DOM tree
        self::traverseNode($dom->documentElement, $currentHeadings, $chunks);
        
        return $chunks;
    }
    
    /**
     * Recursively traverses DOM nodes to build chunks.
     * 
     * @param \DOMNode $node The current DOM node
     * @param array $currentHeadings Array of current headings at each level
     * @param array &$chunks Reference to the chunks array to populate
     */
    private static function traverseNode(\DOMNode $node, array &$currentHeadings, array &$chunks): void
    {
        if ($node->nodeType === XML_TEXT_NODE) {
            return; // Skip text nodes, we'll handle them in content elements
        }
        
        if ($node->nodeType !== XML_ELEMENT_NODE) {
            return; // Skip non-element nodes
        }
        
        $tagName = strtolower($node->nodeName);
        
        // Handle headings
        if (preg_match('/^h([1-6])$/', $tagName, $matches)) {
            $level = (int)$matches[1];
            $headingText = self::extractTextContent($node);
            
            if (!empty(trim($headingText))) {
                // Update current headings array
                $currentHeadings[$level - 1] = $headingText;
                // Remove any deeper headings
                $currentHeadings = array_slice($currentHeadings, 0, $level);
            }
        }
        
        // Handle content elements
        if (self::isContentElement($tagName)) {
            $content = self::extractTextContent($node);
            
            if (!empty(trim($content))) {
                $chunk = self::buildChunk($currentHeadings, $content);
                $chunks[] = $chunk;
            }
        }
        
        // Recursively process child nodes
        foreach ($node->childNodes as $childNode) {
            self::traverseNode($childNode, $currentHeadings, $chunks);
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
        
        return implode(" \\n ", $parts);
    }
}