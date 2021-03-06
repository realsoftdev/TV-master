<?php namespace Vinelab\Youtube;

/**
 * @author Adib
 * @author Mahmoud Zalt <mahmoud@vinelab.com>
 */

use Vinelab\Youtube\Contracts\ApiInterface;
use Vinelab\Youtube\Contracts\ManagerInterface;
use Vinelab\Youtube\Contracts\SynchronizerInterface;
use Vinelab\Youtube\Contracts\Vinelab;
use Vinelab\Youtube\Helpers\YoutubeUrlParser as UrlParser;

class Manager implements ManagerInterface
{

    /**
     * The api instance.
     * @var Vinelab\Youtube\Contracts\ApiInterface
     */
    protected $api;

    /**
     * The synchronizer instance
     * @var Vinelab\Youtube\Contracts\SynchronizerInterface
     */
    protected $synchronizer;

    /**
     * Create a new Manager instance
     *
     * @param ApiInterface          $youtube
     * @param SynchronizerInterface $synchronizer
     */
    public function __construct(
        ApiInterface $api,
        SynchronizerInterface $synchronizer
    ) {
        $this->api = $api;
        $this->synchronizer = $synchronizer;
    }

    /**
     * Return a videos info
     *
     * @param string|array $urls
     *
     * @return \Vinelab\Youtube\Contracts\Vinelab\Youtube\YoutubeVideo
     */
    public function videos($urls)
    {
        if (! is_array($urls)) {
            return $this->api->video(UrlParser::parseId($urls));
        }
        // if array parse each url
        $vids = array_map(function ($url) {
            return UrlParser::parseId($url);
        }, $urls);

        return $this->api->video($vids);
    }

    /**
     * return the channel's videos by id or by username.
     *
     * @param  string $id_or_name
     * @param  date   $synced_at
     *
     * @return Vinelab\Youtube\Channel
     */
    public function videosForChannel($url, $synced_at = null)
    {
        //parse the url and then return the channel id or name
        $id_or_name = UrlParser::parseChannelUrl($url);

        return $this->api->channel($id_or_name, $synced_at);
    }


    /**
     * return the playlist's videos by id or by username.
     *
     * @param  string $id_or_name
     * @param  date   $synced_at
     *
     * @return Vinelab\Youtube\Channel
     */
    public function videosForPlaylist($url, $synced_at = null)
    {
        //parse the url and then return the playlist id or name
        $id_or_name = UrlParser::parsePlaylistUrl($url);

        return $this->api->playlist($id_or_name, $synced_at);
    }


    /**
     * Sync a resource (channel or video)
     *
     * @param  ResourceInterface $resource
     *
     * @return Channel|Video
     */
    public function sync(ResourceInterface $resource)
    {
        if (is_null($resource)) {
            return false;
        }

        return $this->synchronizer->sync($resource);
    }

    /**
     * return the type of object
     *
     * @param  Object $object
     *
     * @return string
     */
    protected function typeOf($object)
    {
        return (isset($object)) ? get_class($object) : null;
    }

    /**
     * add http to the url if it does not exist.
     *
     * @param $url
     *
     * @return string
     */
    public function prepareUrl($url)
    {
        if (!preg_match('/http[s]?:\/\//', $url, $matches)) {
            $url = 'http://' . $url;

            return $url;
        }

        return $url;
    }

    /**
     * Return a video info
     *
     * @param  string $vid
     *
     * @return Vinelab\Youtube\Video
     */
    public function video($vid)
    {
        // TODO: Implement video() method.
    }
}
