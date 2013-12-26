<?php

class cssMinifier {
     
    private $buffer;
    private $cache;
    private $expires;
     
     
    public function __construct($css, $config) {
        if(is_array($css)) {
            foreach($css as $file) {
                if(file_exists($file)) {
                    // Get Stylesheet data
                    $this->buffer .= file_get_contents($file);
                }
            }
        } else {
            if(file_exists($css)) {
                $this->buffer = file_get_contents($file);
             } else {
                throw new Exception('File does not exist');
             }           
        }
         
        if(is_array($config)) {
            $this->cache = false;
            $this->expires = 0;
            if(isset($config['Cache']) && $config['Cache'] === true) $this->cache = true;
            if(isset($config['Expires']) && is_int($config['Expires'])) $this->expires = $config['Expires'];
        }  
         
     // Buffer  
    }
    
    public function process() {
        //Trim
        $this->buffer = trim($this->buffer);
        // Remove comments
        $this->buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $this->buffer);
         
        $this->buffer = str_replace("\r\n", "\n", $this->buffer);
         
        // preserve empty comment after '>'
        // http://www.webdevout.net/css-hacks#in_css-selectors
        $this->buffer = preg_replace('@>/\\*\\s*\\*/@', '>/*keep*/', $this->buffer);
         
        // preserve empty comment between property and value
        // http://css-discuss.incutio.com/?page=BoxModelHack
        $this->buffer = preg_replace('@/\\*\\s*\\*/\\s*:@', '/*keep*/:', $this->buffer);
        $this->buffer = preg_replace('@:\\s*/\\*\\s*\\*/@', ':/*keep*/', $this->buffer);
         
 
        // remove ws around { } and last semicolon in declaration block
        $this->buffer = preg_replace('/\\s*{\\s*/', '{', $this->buffer);
        $this->buffer = preg_replace('/;?\\s*}\\s*/', '}', $this->buffer);
         
        // remove ws surrounding semicolons
        $this->buffer = preg_replace('/\\s*;\\s*/', ';', $this->buffer);
         
        // remove ws around urls
        $this->buffer = preg_replace('/
                url\\(      # url(
                \\s*
                ([^\\)]+?)  # 1 = the URL (really just a bunch of non right parenthesis)
                \\s*
                \\)         # )
            /x', 'url($1)', $this->buffer);
         
        // remove ws between rules and colons
        $this->buffer = preg_replace('/
                \\s*
                ([{;])              # 1 = beginning of block or rule separator
                \\s*
                ([\\*_]?[\\w\\-]+)  # 2 = property (and maybe IE filter)
                \\s*
                :
                \\s*
                (\\b|[#\'"-])        # 3 = first character of a value
            /x', '$1$2:$3', $this->buffer);
         
         
        // minimize hex colors
        $this->buffer = preg_replace('/([^=])#([a-f\\d])\\2([a-f\\d])\\3([a-f\\d])\\4([\\s;\\}])/i'
            , '$1#$2$3$4$5', $this->buffer);
         
          
        $this->buffer = preg_replace('/@import\\s+url/', '@import url', $this->buffer);
         
        // replace any ws involving newlines with a single newline
        $this->buffer = preg_replace('/[ \\t]*\\n+\\s*/', "\n", $this->buffer);
         
        // separate common descendent selectors w/ newlines (to limit line lengths)
        $this->buffer = preg_replace('/([\\w#\\.\\*]+)\\s+([\\w#\\.\\*]+){/', "$1\n$2{", $this->buffer);
         
        // Use newline after 1st numeric value (to limit line lengths).
        $this->buffer = preg_replace('/
            ((?:padding|margin|border|outline):\\d+(?:px|em)?) # 1 = prop : 1st numeric value
            \\s+
            /x'
            ,"$1\n", $this->buffer);
         
        // prevent triggering IE6 bug: http://www.crankygeek.com/ie6pebug/
        $this->buffer = preg_replace('/:first-l(etter|ine)\\{/', ':first-l$1 {', $this->buffer);
         
         
        // Enable GZip encoding.
        ob_start("ob_gzhandler");
         
         
        if($this->cache === true) {
            // Enable caching
            header('Cache-Control: public');
             
            // Expire in one day
            header('Expires: ' . date('D, d M Y H:i:s', time() + $this->expires));        
        }  
         
        // Set the correct MIME type, because Apache won't set it for us
        header("Content-type: text/css");
         
        // Write everything out
        echo $this->buffer;       
    }
// END OF CLASS
}
?>