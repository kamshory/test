<?php

namespace Sipro\Util;

class StringUtil
{
    /**
     * Function to replace the placeholder ${any} with the corresponding value from the object.
     *
     * @param string $template The HTML template containing placeholders
     * @param MagicObject|SetterGetter|SecretObject|MagicDto|PicoGenericObject $object The object with a get() method to retrieve values
     * @return string The template with placeholders replaced by values from the object
     */
    public static function replacePlaceholders($template, $object) {
        // Pattern to search for all placeholders ${any}
        return preg_replace_callback('/\$\{(.*?)\}/', function($matches) use ($object) {
            // Retrieve the value from the object using the get() method with the key found
            return $object->get($matches[1]);
        }, $template); // Use $template, not $html
    }

    /**
     * Convert HTML and CSS content into plain text.
     *
     * @param string $html The HTML content to be converted.
     * @return string The plain text extracted from the HTML content.
     */
    public static function convertHtmlToText($html) {
        // Remove all CSS styles inside <style> tags and inline styles
        $htmlWithoutCss = preg_replace('/<style[^>]*>.*?<\/style>/is', '', $html);  // Remove <style> tags
        $htmlWithoutCss = preg_replace('/style="[^"]*"/i', '', $htmlWithoutCss);     // Remove inline styles

        // Remove HTML tags, leaving only the plain text
        $text = strip_tags($htmlWithoutCss);
        
        // Optionally, you can also remove extra spaces and line breaks if needed
        $text = preg_replace('/\s+/', ' ', $text);  // Replace multiple spaces with a single space
        $text = trim($text);  // Remove leading/trailing whitespace

        return $text;
    }
}