<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Texttospeech { 
     
    /** Max text characters 
     * @var    Integer  
     */ 
    var $maxStrLen = 100; 
     
    /** Text len 
     * @var    Integer  
     */ 
    var $textLen = 0; 
     
    /** No of words 
     * @var    Integer  
     */ 
    var $wordCount = 0; 
     
    /** Language of text (ISO 639-1) 
     * @var    String  
     * @link https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes 
     */ 
    var $lang = 'en'; 
     
    /** Text to speak 
     * @var    String  
     */ 
    var $text = NULL; 
     
    /** File name format 
     * @var    String  
     */ 
    var $mp3File = "%s.wav"; 
     
    /** Directory to store audio file 
     * @var    String  
     */ 
    var $audioDir = "assets/";
     
    function  Texttospeech()
    {
        $this->audioDir= "assets/captcha/";
    }
    
    /** Function make request to Google translate, download file and returns audio file path 
     * @param     String     $text        - Text to speak 
     * @param     String     $lang         - Language of text (ISO 639-1) 
     * @return     String     - mp3 file path 
     * @link https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes 
     */ 
    function speak($filename,$text, $lang = NULL) { 
     
        $this->text = $text; 
        $this->mp3File="assets/captcha/".$filename.".wav";
        // Can not handle more than 100 characters 
        $this->text = substr($this->text, 0, $this->maxStrLen); 
     
        // Text lenght 
        $this->textLen = strlen($this->text); 
         
        // Words count 
        $this->wordCount = str_word_count($this->text); 
         
        // Encode string 
        $this->text = urlencode($this->text); 
         
        // Create dir if not exists 
        if (!is_dir($this->audioDir)) { 
            mkdir($this->audioDir, 777) or die('Could not create audio dir: ' . $this->audioDir); 
        } 
         
        // Generate unique mp3 file name 
       // $this->mp3File = sprintf($this->mp3File, $this->audioDir); 
         
        // Download new file or use existing  
        if (!file_exists($this->mp3File)) { 
            $this->download("http://translate.google.com/translate_tts?ie=UTF-8&q={$this->text}&tl={$this->lang}&total={$this->wordCount}&idx=0&textlen={$this->textLen}", $this->mp3File); 
        } 
         
        // Returns mp3 file path 
        return $this->mp3File; 
    } 
     
    /** Function to download and save file 
     * @param     String     $url        - URL 
     * @param     String     $path         - Local path 
     */  
    function download($url, $path){  
        // Is curl installed? 
        if (!function_exists('curl_init')){ // use file get contents  
            $output = file_get_contents($url);  
        }else{ // use curl  
            $ch = curl_init();  
            curl_setopt($ch, CURLOPT_URL, $url);  
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);  
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");  
            curl_setopt($ch, CURLOPT_HEADER, 0);  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);  
            $output = curl_exec($ch);  
            curl_close($ch);  
        } 
        // Save file 
        file_put_contents($this->mp3File, $output); 
    } 
}
