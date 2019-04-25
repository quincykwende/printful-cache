<?php

//Defining the namespace
namespace PrintfulCache;
use Exception;
use PrintfulCache\File;
use PrintfulCache\CacheInterface;

class FileCache implements CacheInterface{

	/**
	* Directory to store cached files
	* @string
	*/
	protected $directory;

	/** 
	*  Construct 
	*  @param string
	*  @return void
	*/
	public function __construct()
    {
        //get cache directory from file config
        $config = include_once "./config/printful-cache.php";
        $this->directory = $config['directory'];
        $this->files = new File();
    }

     /**
     * Retrieve stored item from cache by key.
     * Returns the same type as it was stored in.
     * Returns null if entry has expired.
     *
     * @param  string|array  $key
     * @return mixed
     */
    public function get(string $key)
    {
        $path = $this->path($key);

        //now get file; if file exist and time < expiry time return data else return null
        $content = $this->files->get($path);
        if($content != ''){
            
            //current time
            $current_time = (new \DateTime())->getTimestamp();
        
            //the expirytimestamp is the first 10 characters on the data
            $expiry_time = substr($content, 0, 10);

            if($expiry_time >= $current_time){
                //return content
                return unserialize(substr($content, 10));
            }else{
                //delete file and return null
                $this->files->delete($path);
                return null;
            }
        }

        return null;
        
    }

    /**
     * Store a mixed type value in cache for a certain amount of seconds.
     * Allowed values are primitives and arrays.
     *
     * @param string  $key
     * @param mixed   $value
     * @param int $duration Duration in seconds
     * @return mixed
     */
    public function set(string $key, $value, int $duration)
    {   
        try{
            //set the path for the cache file
            $path = $this->path($key);
            //concatinate expirytimestamp with the content
            $content = $this->expiryTimestamp($duration).serialize($value);
            //insert/overwrite cache file if already exist
            $this->files->write($path, $content);

            return $this->get($key);

        }catch(Exception $e){
            echo $e->getMessage();
        }
            
    }

    /**
    * Returns the full path of a key
    *
    * @param string $key
    * @return string
    */
    protected function path($key){
        $uri = sha1($key);
        return $this->directory.'/'.$uri;
    }

    /**
    * Returns the expiry time in unix timestamp; 
    *
    * @param int $duration in seconds
    * @return int 
    */
    protected function expiryTimestamp($duration){
        //current datetime
        $date = new \DateTime();
        //add seconds
        $date->add(new \DateInterval("PT{$duration}S"));
        //return timestamp
        return $date->getTimestamp();
    }

}