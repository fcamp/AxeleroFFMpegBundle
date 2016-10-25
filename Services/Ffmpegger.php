<?php
namespace Axelero\FfmpegBundle\Services;

use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;

class Ffmpegger
{
    /**
     * @var FFMpeg
     */
    private $ffmpeg;

    /**
     * @var string
     */
    private $baseFolder;

    /**
     * Ffmpegger constructor.
     * @param FFMpeg $ffmpeg
     * @param $baseFolder
     */
    public function __construct(FFMpeg $ffmpeg, $baseFolder)
    {
        $this->ffmpeg = $ffmpeg;
        $this->baseFolder = $baseFolder;
    }


    /**
     * Save a frame from the video
     * @param string $path video path relative to baseFolder
     * @param int $second the second you want to extract the frame at
     * @return null|string the frame path or null
     */
    public function extractFrameAt($path, $second = null)
    {
        //getting info
        $info = pathinfo($path);
        $absolutePath = realpath($this->baseFolder . '/' . $path);


        $absoluteInfo = pathinfo($absolutePath);

        //path
        $framePath = $info['dirname'] . '/' . $info['filename'] . '.jpg';
        $video = $this->ffmpeg->open($absolutePath);

        //extracting at given second or randomly
        $duration = $this->ffmpeg->getFFProbe()->format($absolutePath)->get('duration');
        $at = is_null($second) ? rand(0, $duration) : $second;

        $frame = $video->frame(TimeCode::fromSeconds($at));

        return $frame->save($absoluteInfo['dirname'] . '/' . $absoluteInfo['filename'] . '.jpg') ? $framePath : null;
    }


}