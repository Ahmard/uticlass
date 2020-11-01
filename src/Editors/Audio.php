<?php

namespace Uticlass\Editors;

use Exception;

require_once(dirname(__FILE__, 5) . '/james-heinrich/getid3/getid3/getid3.php');
require_once(dirname(__FILE__, 5) . '/james-heinrich/getid3/getid3/write.php');

/**
 * Manipulate audio files tags
 * @package Uticlass\Editors
 */
class Audio
{
    protected string $file;
    
    protected array $tagData = [];
    
    protected array $options = [
        'tag_encoding' => 'UTF-8',
        'tagformats' => array('id3v2.3'),
        'overwrite_tags' => true,
        'remove_other_tags' => false,
    ];

    /**
     * Audio constructor.
     * @param string $file file to edit
     */
    public function __construct(string $file)
    {
        if(! file_exists($file)){
            throw new Exception("File {$file} does not exists.");
        }
        
        $this->options['filename'] = $file;
        $this->file = $file;
    }

    /**
     * Set/update mp3 tag
     * @param string|array $name
     * @param string $value
     * @return $this
     */
    public function setTag($name, string $value = '')
    {
        if(is_array($name)){
            array_merge($this->tagData, $name);
            return $this;
        }
        
        $this->tagData[$name] = [$value];
        return $this;
    }

    /**
     * Set option to \getid3_writetags class
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function setOption(string $name, string $value = '')
    {
        if(is_array($name)){
            array_merge($this->options, $name);
            return $this;
        }
        
        $this->options[$name] = $value;
        return $this;
    }

    /**
     * A convenient method to edit mp3 file lyric
     * @param string $lyric
     * @return $this
     */
    public function setLyric(string $lyric)
    {
        $this->tagData['unsynchronised_lyric'] = [$lyric];
        return $this;
    }

    /**
     * Save changes made to mp3 file
     * @return array|bool
     */
    public function save()
    {
        // Initialize getID3 engine
        $getID3 = new \getID3;
        $getID3->setOption(array('encoding'=>$this->options['tag_encoding']));

        $tagWriter = new \getid3_writetags();
        
        foreach ($this->options as $optionName => $optionValue){
            $tagWriter->$optionName = $optionValue;
        }
        
        $tagWriter->tag_data = $this->tagData;
        
        if ($tagWriter->WriteTags()) {
            return true;
        }
        
        return $tagWriter->errors;
    }
}