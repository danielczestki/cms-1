<?php

namespace Thinmartian\Cms\App\Services\Media;

use Thinmartian\Cms\App\Models\Core\CmsMediumVideo;
use Aws\ElasticTranscoder\ElasticTranscoderClient;
use Storage;

class Video extends Media
{
    
    /**
     * @var Aws\ElasticTranscoder\ElasticTranscoderClient
     */
    protected $elastic;
    
    /**
     * Name of the pipeline
     */
    const PIPELINE = "cms_video_pipeline";
    
    //
    // Output
    //  
    
    /**
     * Fetch the URL to the video or false if still encoding
     * 
     * @param  integer  $cms_medium_id  The cms_medium_id we are getting
     * @return mixed
     */
    public function get($cms_medium_id)
    {
        // Set and check
        $this->setCmsMedium($cms_medium_id);
        if ($this->cmsMedium->type != "video") return false;
        if ($this->getStatus()) {
            // It's ready
            return "/path/to/video";
        } else {
            // Still waiting...
            return false;
        }
    }
    
    /**
     * Fetch the URL to the video thumb or false if still encoding
     * 
     * @param  integer  $cms_medium_id  The cms_medium_id we are getting
     * @return mixed
     */
    public function thumbnail($cms_medium_id)
    {
        // Set and check
        $this->setCmsMedium($cms_medium_id);
        if ($this->cmsMedium->type != "video") return false;
        if ($this->getStatus()) {
            // It's ready
            return $this->getPublicUrl("cms/media/{$this->cmsMedium->id}/encoded/{$this->cmsMedium->filename}-00001.png");
        } else {
            // Still wating...
            return false;
        }
    }
    
    /**
     * Determine if the video is processed yet
     * @return boolean
     */
    private function getStatus()
    {
        // First, we check the DB for it's status
        if (in_array($this->cmsMedium->video->job_status, ["submitted", "progressing"])) {
            // Still processing, make a call to Amazon and see where we are at?
            $this->setElastic();
            $job = $this->elastic->readJob(["Id" => $this->cmsMedium->video->job_id]);
            $status = $job->get("Job")["Status"];
            $this->cmsMedium->video->job_status = $status;
            $this->cmsMedium->video->save();
            return strtolower($status) == "complete";
        } else {
            // All done, move on
            return true;
        }
    }
    
    //
    // CRUD
    // 
    
    /**
     * Store the image
     * 
     * @return App\Cms\CmsMedium
     */
    public function store()
    {
        // create the parent media item first
        $this->storeCmsMedium();
        // lets upload the raw file
        $this->upload();
        // encode the video
        $job = $this->encode();        
        // now persist the video
        if ($this->uploadedFile->uploaded) {
            $resource = new CmsMediumVideo;
            $resource->job_status = $job->get("Job")["Status"];
            $resource->job_id = $job->get("Job")["Id"];
            $resource->arn = $job->get("Job")["Arn"];
            $resource->pipeline_id = $job->get("Job")["PipelineId"];
            $this->cmsMedium->video()->save($resource);
        }
        // Return the model back to the controller
        return $this->cmsMedium;    
    }
    
    
    //
    // Encode
    // 
    
    /**
     * Send to transcoder to encoding (wrapper to create pipeline and job)
     * 
     * @return array
     */
    public function encode()
    {
        // Set the elastic property
        $this->setElastic();
        // Create the job
        return $this->createJob();
    }
    
    /**
     * Create the job for this video
     */
    public function createJob()
    {
        $path = $this->getOriginalPath(true);
        $result = $this->elastic->createJob([
            "PipelineId" => config("cms.cms.media_pipeline_id"),
            "OutputKeyPrefix" => "cms/media/{$this->cmsMedium->id}/encoded/",
            "Input" => [
                "Key" => $path,
                "FrameRate" => "auto",
                "Resolution" => "auto",
                "AspectRatio" => "auto",
                "Interlaced" => "auto",
                "Container" => "auto",
            ],
            "Output" => [
                "Key" => "{$this->uploadedFile->filename}.{$this->uploadedFile->extension}",
                "PresetId" => config("cms.cms.media_preset_id"),
                "ThumbnailPattern" => $this->uploadedFile->filename . "-{count}"
            ]
            
        ]);
        return $result;
    }
    
    /**
     * Set the elastic property to make calls to amazon
     */
    protected function setElastic()
    {
        $this->elastic = ElasticTranscoderClient::factory(array(
            "credentials" => [
                "key" => config("filesystems.disks.s3.key"),
                "secret" => config("filesystems.disks.s3.secret"),
            ],
            "region" => config("filesystems.disks.s3.region"),
            "version" => "latest"
        ));
    }
    
    
    //
    // Validation
    // 
    
    /**
     * Get the form validation rules on create
     * 
     * @return array
    */
    public function validationOnCreate()
    {
        return [
            "type" => "required|in:". implode(",", array_keys($this->getMediaTypes())),
            "title" => "required|max:100",
            "file" => "required|file|mimes:". implode(",", $this->getMediaTypes("video.accepted"))
        ];
    }
    
    /**
     * Get the form validation rules on update
     * 
     * @return array
    */
    public function validationOnUpdate()
    {
        return [
            "title" => "required|max:100",
            "file" => "file|mimes:". implode(",", $this->getMediaTypes("video.accepted"))
        ];
    }
    
    //
    // Redirects
    // 

    /**
     * Redirect the user after store
     * 
     * @return  Illuminate\Routing\Redirector
    */
    public function redirectOnStore($cms_medium_id) 
    {
        return redirect()->route("admin.media.index")->withSuccess("Video successfully saved!");
    }
    
    /**
     * Redirect the user after update
     * 
     * @return  Illuminate\Routing\Redirector
    */
    public function redirectOnUpdate($cms_medium_id) 
    {
        return redirect()->route("admin.media.index")->withSuccess("Video successfully saved!");
    }
    
}