<?php

//Defining the namespace
namespace PrintfulCache;

class File {

	
     /**
     * Returns the content of a file as string;
     * Reference: Laravel style file get function
     *
     * @param  string  $path
     * @return string
     */
    public function get($path)
    {
        /**
        instead of using file_get_content with is more trivial; this approach is preferably in case a file is already open. 
        For this task it wouldn't be important because only one person will 
        be testing my work; However, if it is deployed to production and a 
        file is already open by another instance; then we would be in trouble :) 
        **/
        try{
            $content = null;
            //check if file exist 
            if (file_exists($filename)) {
                $handle = fopen($path, 'rb');

                if($handle){
                    try{
                        if(flock($handle, LOCK_SH)){
                            clearstatcache(true, $path);
                            $content = fread($handle, filesize($path) ?: 1);
                            flock($handle, LOCK_UN);
                        }
                    }finally {
                        fclose($handle);
                    }
                }
                return $content;
            }else{
                return null;
            }
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    /**
     * Writes content to a file; insert/overwrite file if already exist
     *
     * @param string  $path
     * @param string  $content
     * @return mixed
     */
    public function write($path, $content)
    {   
       return file_put_contents($path, $content); 
    }

    /**
     * Delete file
     *
     * @param string  $path
     * @return void
     */
    public function delete($path){
        unlink($path);
    }

    

}